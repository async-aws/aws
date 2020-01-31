<?php

declare(strict_types=1);

namespace AsyncAws\Build\Generator;

use AsyncAws\Core\Result;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PsrPrinter;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ApiGenerator
{
    /**
     * @var string
     */
    private $srcDirectory;

    public function __construct(string $srcDirectory)
    {
        $this->srcDirectory = $srcDirectory;
    }

    /**
     * Update the API client with a new function call.
     */
    public function generateOperation($definition, $service, $operationName): void
    {
        $operation = $definition['operations'][$operationName];

        $baseNamespace = \sprintf('AsyncAws\\%s', $service);
        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $inputShape = $definition['shapes'][$operation['input']['shape']] ?? [];

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        $class->removeMethod(\lcfirst($operation['name']));
        $method = $class->addMethod(\lcfirst($operation['name']));
        if (isset($operation['documentationUrl'])) {
            $method->addComment('@see ' . $operation['documentationUrl']);
        }
        $method->addComment('@param array{');
        foreach ($inputShape['members'] as $name => $data) {
            $nullable = !\in_array($name, $inputShape['required'] ?? []);
            $param = $this->toPhpType($definition['shapes'][$data['shape']]['type']);

            $method->addComment(sprintf('  %s%s: %s', $name, $nullable ? '?' : '', $param));
        }
        $method->addComment('} $input');

        $method->addParameter('input')->setType('array');

        $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, $operation['output']['shape']);
        $method->setReturnType($outputClass);
        $namespace->addUse($outputClass);

        // Generate method body
        $body = <<<PHP
\$uri = [];
\$query = [];
\$headers = [];


PHP;;

        foreach (['header' => '$headers', 'querystring' => '$query', 'uri' => '$uri'] as $locationName => $varName) {
            foreach ($inputShape['members'] as $name => $data) {
                $location = $data['location'] ?? null;
                if ($location === $locationName) {
                    $body .= 'if (array_key_exists("'.$name.'", $input)) '.$varName.'["'.$data['locationName'].'"] = $input["'.$name.'"];'."\n";
                }
            }
        }

        if (!isset($inputShape['payload'])) {
            $body.='$payload = "";';
        } else {
            $data = $inputShape['members'][$inputShape['payload']];
            if ($data['streaming'] ?? false) {
                $body .= '$payload = $input["'.$inputShape['payload'].'"];';
            } else {
                // Build XML
                $document = new \DOMDocument('1.0', 'UTF-8');
                $root = $document->createElement($data['locationName']);
                $document->appendChild($root);
                if (isset($data['xmlNamespace']['uri'])) {
                    $root->setAttribute('xmlns', $data['xmlNamespace']['uri']);
                }
                // Build children
                $this->buildXml($document, $root, $definition['shapes'], $data['shape']);

                $document->formatOutput = true;
                $body .= '$payload = <<<XML'."\n".$document->saveXML()."\nXML;";
            }
        }

        $method->setBody($body.
            <<<PHP

\$response = \$this->getResponse('{$operation['http']['method']}', \$payload, \$headers, \$this->getEndpoint(\$uri));
return new {$operation['output']['shape']}(\$response);
PHP
        );

        $printer = new PsrPrinter();
        $filename = \sprintf('%s/%s/%sClient.php', $this->srcDirectory, $service, $service);
        \file_put_contents($filename, "<?php\n\n" . $printer->printNamespace($namespace));

        // clean HEREDOC
        $rows = file($filename);
        $heredoc = null;
        foreach ($rows as $i => $row) {
            if (null === $heredoc) {
                if (preg_match('#<<<([^ ]+)$#si', $row, $match)) {
                    $heredoc = trim($match[1]);
                    $rows[$i + 1] = ltrim($rows[$i + 1]);
                }
            } elseif (preg_match('#'.$heredoc.';$#s', $row)) {
                $heredoc = null;
                $rows[$i] = ltrim($rows[$i]);
            }
        }
        \file_put_contents($filename, \implode('', $rows));
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generateResultClass(array $shapes, $service, $baseNamespace, $className, $root = false)
    {
        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass($className);
        $members = $shapes[$className]['members'];

        if ($root) {
            $traitName = $className . 'Trait';
            $namespace->addUse(Result::class);
            $class->addExtend(Result::class);
            $class->addTrait($baseNamespace . '\\' . $traitName);

            // Add trait only if file does not exists
            $traitFilename = \sprintf('%s/%s/Result/%s.php', $this->srcDirectory, $service, $traitName);
            if (!\file_exists($traitFilename)) {
                $this->createOutputTrait($baseNamespace, $traitName, $members, $traitFilename);
            }
        }

        foreach ($members as $name => $data) {
            $class->addProperty($name)->setPrivate();
            $parameterType = $members[$name]['shape'];

            // TODO if $shapes[$parameterType]['type'] === 'struct' ??
            if (!\in_array($shapes[$parameterType]['type'], ['string', 'boolean', 'long', 'timestamp', 'integer', 'map', 'blob', 'list'])) {
                if (!isset($shapes[$parameterType]['members'])) {
                    throw new \RuntimeException(\sprintf('Unexpected type "%s". Not sure how to handle this.', $shapes[$parameterType]['type']));
                }
                $this->generateResultClass($shapes, $service, $baseNamespace, $parameterType);
            } else {
                $parameterType = $this->toPhpType($shapes[$parameterType]['type']);
            }

            $callInitialize = '';
            if ($root) {
                $callInitialize = <<<PHP
\$this->initialize();
PHP;
            }

            $nullable = !\in_array($name, $shapes[$className]['required'] ?? []);
            $class->addMethod('get' . $name)
                ->setReturnType($parameterType)
                ->setReturnNullable($nullable)
                ->setBody(
                    <<<PHP
$callInitialize
return \$this->{$name};
PHP
                );
        }

        $printer = new PsrPrinter();
        \file_put_contents(\sprintf('%s/%s/Result/%s.php', $this->srcDirectory, $service, $className), "<?php\n\n" . $printer->printNamespace($namespace));
    }

    private function createOutputTrait($baseNamespace, string $traitName, $members, string $traitFilename)
    {
        $namespace = new PhpNamespace($baseNamespace);
        $namespace->addUse(ResponseInterface::class);
        $trait = $namespace->addTrait($traitName);

        $body = '$data = new \SimpleXMLElement($response->getContent(false));' . "\n\n// TODO Verify correctness\n";
        foreach (\array_keys($members) as $name) {
            $body .= "\$this->$name = \$data->$name;\n";
        }

        $trait->addMethod('populateFromResponse')
            ->setReturnType('void')
            ->setProtected()
            ->setBody($body)
            ->addParameter('response')->setType(ResponseInterface::class);

        $printer = new PsrPrinter();
        \file_put_contents($traitFilename, "<?php\n\n" . $printer->printNamespace($namespace));
    }

    /**
     * Here is an examples what $shapes[$shapeName] might look like:
     *
     * 'AccessControlPolicy' => [
     *      'type' => 'structure',
     *      'members' => [
     *          'Grants' => ['shape' => 'Grants', 'locationName' => 'AccessControlList',],
     *          'Owner' => ['shape' => 'Owner',],
     *      ],
     *  ],
     *
     * $parentElement is the DOM element representing AccessControlPolicy, Our job is to create
     * the members.
     *
     */
    private function buildXml(\DOMDocument $document, \DOMElement $parentElement, array $shapes, string $shapeName, string $inputPrefix = '')
    {
        $shape = $shapes[$shapeName];
        $members = $shape['members'] ?? ($shape['member'] ? [$shape['member']] : []);
        foreach ($members ?? [] as $name => $member) {
            $el = $document->createElement($member['locationName'] ?? $name);
            $parentElement->appendChild($el);

            if (empty($inputPrefix)) {
                $inputPrefix = '$input';
            }
            if (is_int($name)) {
                $input = $inputPrefix.'['.$name.']';
            } else {
                $input = $inputPrefix.'["'.$name.'"]';
            }

            if (in_array($shapes[$member['shape']]['type'], ['structure', 'list'])) {
                // We need a child
                $this->buildXml($document, $el, $shapes, is_int($name) ? $member['shape'] : $name, $input);
            } else {
                $el->nodeValue = '{'.$input.'}';
            }
        }
    }

    private function toPhpType(string $parameterType): string
    {
        if ('boolean' === $parameterType) {
            $parameterType = 'bool';
        } elseif (\in_array($parameterType, ['integer', 'timestamp'])) {
            $parameterType = 'int';
        } elseif (\in_array($parameterType, ['blob', 'long'])) {
            $parameterType = 'string';
        } elseif (\in_array($parameterType, ['map', 'list'])) {
            $parameterType = 'array';
        }

        return $parameterType;
    }
}
