<?php

declare(strict_types=1);

namespace AsyncAws\Build\Generator;

use AsyncAws\Core\Result;
use AsyncAws\Core\XmlBuilder;
use Nette\PhpGenerator\Method;
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
        $this->addMethodComment($definition, $method, $inputShape);

        $method->addParameter('input')->setType('array');

        $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, $operation['output']['shape']);
        $method->setReturnType($outputClass);
        $namespace->addUse($outputClass);
        $namespace->addUse(XmlBuilder::class);

        // Generate method body
        $this->setMethodBody($definition, $inputShape, $method, $operation);

        $printer = new PsrPrinter();
        $filename = \sprintf('%s/%s/%sClient.php', $this->srcDirectory, $service, $service);
        \file_put_contents($filename, "<?php\n\n" . $printer->printNamespace($namespace));
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

            if ('structure' === $shapes[$parameterType]['type']) {
                $this->generateResultClass($shapes, $service, $baseNamespace, $parameterType);
                $parameterType = $baseNamespace . '\\' . $parameterType;
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

    private function toPhpType(?string $parameterType): string
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

    /**
     * Pick only the config from $shapes we are interested in.
     */
    private function buildXmlConfig(array $shapes, string $shapeName): array
    {
        $shape = $shapes[$shapeName];
        if (!\in_array($shape['type'] ?? 'structure', ['structure', 'list'])) {
            $xml[$shapeName]['type'] = $this->toPhpType($shape['type']);

            return $xml;
        }

        $xml[$shapeName]  = $shape;
        $members = [];
        if (isset($shape['members'])) {
            $members = $shape['members'];
        } elseif (isset($shape['member'])) {
            $members = [$shape['member']];
        }

        foreach ($members as $name => $data) {
            $xml = array_merge($xml, $this->buildXmlConfig($shapes, $data['shape'] ?? $name));
        }

        return $xml;
    }

    private function printArray(array $data): string
    {
        $output = '[';
        foreach ($data as $name => $value) {
            $output.= sprintf('%s => %s,', (\is_int($name) ? $name : '"' . $name . '"'), \is_array($value) ? $this->printArray($value) : ("'" . $value . "'"));
        }
        $output .= ']';

        return $output;
    }

    private function addMethodComment(array $definition, Method $method, array $inputShape): void
    {
        $method->addComment('@param array{');
        foreach ($inputShape['members'] as $name => $data) {
            $nullable = !\in_array($name, $inputShape['required'] ?? []);
            $param = $this->toPhpType($definition['shapes'][$data['shape']]['type']);
            if (\in_array($param, ['structure', 'list'])) {
                $param = 'array';
            }

            $method->addComment(sprintf('  %s%s: %s', $name, $nullable ? '?' : '', $param));
        }
        $method->addComment('} $input');
    }

    private function setMethodBody(array $definition, array $inputShape, Method $method, array $operation): void
    {
        $body = <<<PHP
\$uri = [];
\$query = [];
\$headers = [];

PHP;
        ;

        foreach (['header' => '$headers', 'querystring' => '$query', 'uri' => '$uri'] as $locationName => $varName) {
            foreach ($inputShape['members'] as $name => $data) {
                $location = $data['location'] ?? null;
                if ($location === $locationName) {
                    $body .= 'if (array_key_exists("' . $name . '", $input)) ' . $varName . '["' . $data['locationName'] . '"] = $input["' . $name . '"];' . "\n";
                }
            }
        }

        if (!isset($inputShape['payload'])) {
            $body .= '$payload = "";';
        } else {
            $data = $inputShape['members'][$inputShape['payload']];
            if ($data['streaming'] ?? false) {
                $body .= '$payload = $input["' . $inputShape['payload'] . '"];';
            } else {
                // Build XML
                $xml = $this->buildXmlConfig($definition['shapes'], $data['shape']);
                $xml['_root'] = [
                    'type' => $data['shape'],
                    'xmlName' => $data['locationName'],
                    'uri' => $data['xmlNamespace']['uri'] ?? '',
                    //'members' => array_keys($definition['shapes'][$data['shape']]['members']),
                ];

                $body .= '$xmlConfig = ' . $this->printArray($xml) . ";\n";
                $body .= '$payload = (new XmlBuilder($input["' . $inputShape['payload'] . '"], $xmlConfig))->getXml();';
            }
        }

        $method->setBody(
            $body .
            <<<PHP

\$response = \$this->getResponse('{$operation['http']['method']}', \$payload, \$headers, \$this->getEndpoint(\$uri, \$query));
return new {$operation['output']['shape']}(\$response);
PHP
        );
    }
}
