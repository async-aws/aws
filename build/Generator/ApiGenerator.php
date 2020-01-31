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

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        $class->removeMethod(\lcfirst($operation['name']));
        $method = $class->addMethod(\lcfirst($operation['name']));
        if (isset($operation['documentationUrl'])) {
            $method->addComment('@see ' . $operation['documentationUrl']);
        }

        $method->addParameter('input')->setType('array');

        $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, $operation['output']['shape']);
        $method->setReturnType($outputClass);
        $namespace->addUse($outputClass);

        $method->setBody(
            <<<PHP
\$input['Action'] = '{$operation['name']}';
\$response = \$this->getResponse('{$operation['http']['method']}', \$input);
return new {$operation['output']['shape']}(\$response);
PHP
        );

        $printer = new PsrPrinter();
        \file_put_contents(
            \sprintf('%s/%s/%sClient.php', $this->srcDirectory, $service, $service),
            "<?php\n\n" . $printer->printNamespace($namespace)
        );
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

            if (!\in_array($shapes[$parameterType]['type'], ['string', 'boolean', 'long', 'timestamp', 'integer', 'map', 'blob', 'list'])) {
                if (!isset($shapes[$parameterType]['members'])) {
                    throw new \RuntimeException(\sprintf('Unexpected type "%s". Not sure how to handle this.', $shapes[$parameterType]['type']));
                }
                $this->generateResultClass($shapes, $service, $baseNamespace, $parameterType);
            } else {
                $parameterType = $shapes[$parameterType]['type'];
                if ('boolean' === $parameterType) {
                    $parameterType = 'bool';
                } elseif (\in_array($parameterType, ['integer', 'timestamp'])) {
                    $parameterType = 'int';
                } elseif (\in_array($parameterType, ['blob', 'long'])) {
                    $parameterType = 'string';
                } elseif (\in_array($parameterType, ['map', 'list'])) {
                    $parameterType = 'array';
                }
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
}
