<?php

declare(strict_types=1);

namespace AsyncAws\Build\Generator;

use AsyncAws\Core\Result;
use AsyncAws\Core\XmlBuilder;
use Nette\PhpGenerator\ClassType;
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

    /**
     * All public classes take a definition as first parameter.
     *
     * @var ServiceDefinition
     */
    private $definition;

    public function __construct(string $srcDirectory)
    {
        $this->srcDirectory = $srcDirectory;
    }

    /**
     * Update the API client with a new function call.
     */
    public function generateOperation(ServiceDefinition $definition, string $service, string $operationName): void
    {
        $this->definition = $definition;
        $operation = $definition->getOperation($operationName);
        $inputShape = $definition->getShape($operation['input']['shape']) ?? [];

        $baseNamespace = \sprintf('AsyncAws\\%s', $service);
        $inputClassName = $operation['input']['shape'];
        $this->generateInputClass($service, $operationName, $baseNamespace . '\\Input', $inputClassName, true);
        $inputClass = $baseNamespace . '\\Input\\' . $inputClassName;

        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $namespace->addUse($inputClass);
        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        $class->removeMethod(\lcfirst($operation['name']));
        $method = $class->addMethod(\lcfirst($operation['name']));
        if (isset($operation['documentationUrl'])) {
            $method->addComment('@see ' . $operation['documentationUrl']);
        }
        // TODO add Input object
        $method->addComment('@param array{');
        $this->addMethodComment($method, $inputShape, $baseNamespace . '\\Input');
        $method->addComment('}|' . $inputClassName . ' $input');
        $method->addParameter('input');

        $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, $operation['output']['shape']);
        $method->setReturnType($outputClass);
        $namespace->addUse($outputClass);
        $namespace->addUse(XmlBuilder::class);

        // Generate method body
        $this->setMethodBody($inputShape, $method, $operation, $inputClassName);

        $printer = new PsrPrinter();
        $filename = \sprintf('%s/%s/%sClient.php', $this->srcDirectory, $service, $service);
        \file_put_contents($filename, "<?php\n\n" . $printer->printNamespace($namespace));
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generateResultClass(ServiceDefinition $definition, string $service, string $baseNamespace, string $className, $wrapper = null, bool $root = false)
    {
        $this->definition = $definition;
        $shapes = $definition->getShapes();
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
                $this->createOutputTrait($baseNamespace, $traitName, $members, $wrapper, $traitFilename);
            }
        }

        foreach ($members as $name => $data) {
            $class->addProperty($name)->setPrivate();
            $parameterType = $members[$name]['shape'];

            if ('structure' === $shapes[$parameterType]['type']) {
                $this->generateResultClass($this->definition, $service, $baseNamespace, $parameterType);
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

    /**
     * Generate classes for the input.
     */
    private function generateInputClass(string $service, string $operationName, string $baseNamespace, string $className, bool $root = false)
    {
        $operation = $this->definition->getOperation($operationName);
        $shapes = $this->definition->getShapes();
        $inputShape = $shapes[$className] ?? [];
        $members = $inputShape['members'];

        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass($className);
        // Add named constructor
        $class->addMethod('create')->setStatic(true)->setReturnType('self')->setBody(
            <<<PHP
return \$input instanceof self ? \$input : new self(\$input);
PHP
        )->addParameter('input');
        $constructor = $class->addMethod('__construct');

        if ($root) {
            if (isset($operation['documentationUrl'])) {
                $constructor->addComment('@see ' . $operation['documentationUrl']);
            }
        }

        $constructor->addComment('@param array{');
        $this->addMethodComment($constructor, $inputShape, $baseNamespace);
        $constructor->addComment('} $input');
        $constructor->addParameter('input')->setType('array')->setDefaultValue([]);
        $constructorBody = '';

        foreach ($members as $name => $data) {
            $parameterType = $members[$name]['shape'];
            $memberShape = $shapes[$parameterType];
            $nullable = true;
            if ('structure' === $memberShape['type']) {
                $this->generateInputClass($service, $operationName, $baseNamespace, $parameterType);
                $returnType = $baseNamespace . '\\' . $parameterType;
                $constructorBody .= sprintf('$this->%s = isset($input["%s"]) ? %s::create($input["%s"]) : null;' . "\n", $name, $name, $parameterType, $name);
            } elseif ('list' === $memberShape['type']) {
                $parameterType = $memberShape['member']['shape'] . '[]';
                $this->generateInputClass($service, $operationName, $baseNamespace, $memberShape['member']['shape']);
                $returnType = $baseNamespace . '\\' . $memberShape['member']['shape'];
                $constructorBody .= sprintf('$this->%s = array_map(function($item) { return %s::create($item); }, $input["%s"] ?? []);' . "\n", $name, $memberShape['member']['shape'], $name);
                $nullable = false;
            } else {
                $returnType = $parameterType = $this->toPhpType($memberShape['type']);
                $constructorBody .= sprintf('$this->%s = $input["%s"] ?? null;' . "\n", $name, $name);
            }

            $property = $class->addProperty($name)->setPrivate();
            if (\in_array($name, $shapes[$className]['required'] ?? [])) {
                $property->addComment('@required');
            }
            $property->addComment('@var ' . $parameterType . ($nullable ? '|null' : ''));

            $returnType = '[]' === substr($parameterType, -2) ? 'array' : $returnType;

            $class->addMethod('get' . $name)
                ->setReturnType($returnType)
                ->setReturnNullable($nullable)
                ->setBody(
                    <<<PHP
return \$this->{$name};
PHP
                );

            $class->addMethod('set' . $name)
                ->setReturnType('self')
                ->setBody(
                    <<<PHP
\$this->{$name} = \$value;
return \$this;
PHP
                )
                ->addParameter('value')->setType($returnType)->setNullable($nullable)
            ;
        }

        $constructor->setBody($constructorBody);
        if ($root) {
            $this->inputClassRequestGetters($inputShape, $class, $operationName);
        }

        $printer = new PsrPrinter();
        \file_put_contents(\sprintf('%s/%s/Input/%s.php', $this->srcDirectory, $service, $className), "<?php\n\n" . $printer->printNamespace($namespace));
    }

    private function inputClassRequestGetters(array $inputShape, ClassType $class, string $operation): void
    {
        foreach (['header' => '$headers', 'querystring' => '$query', 'payload' => '$payload'] as $requestPart => $varName) {
            $body[$requestPart] = $varName . ' = [];' . "\n";
            if ('payload' === $requestPart) {
                $body[$requestPart] = $varName . " = ['Action' => '$operation'];\n";
            }
            foreach ($inputShape['members'] as $name => $data) {
                // If location is not specified, it will go in the request body.
                $location = $data['location'] ?? 'payload';
                if ($location === $requestPart) {
                    $body[$requestPart] .= 'if ($this->' . $name . ' !== null) ' . $varName . '["' . ($data['locationName'] ?? $name) . '"] = $this->' . $name . ';' . "\n";
                }
            }

            $body[$requestPart] .= 'return ' . $varName . ';' . "\n";
        }

        $class->addMethod('requestHeaders')->setReturnType('array')->setBody($body['header']);
        $class->addMethod('requestQuery')->setReturnType('array')->setBody($body['querystring']);
        $class->addMethod('requestBody')->setReturnType('array')->setBody($body['payload']);

        foreach ($inputShape['members'] as $name => $data) {
            if ('uri' === ($data['location'] ?? null)) {
                if (!isset($body['uri'])) {
                    $body['uri'] = '$uri = [];' . "\n";
                }
                $body['uri'] .= <<<PHP
\$uri['{$data['locationName']}'] = \$this->$name ?? '';

PHP;
            }
        }

        $requestUri = $this->definition->getOperation($operation)['http']['requestUri'];
        $body['uri'] = $body['uri'] ?? '';
        $body['uri'] .= 'return "' . str_replace(['{', '+}', '}'], ['{$uri[\'', '}', '\']}'], $requestUri) . '";';

        $class->addMethod('requestUri')->setReturnType('string')->setBody($body['uri']);
    }

    private function createOutputTrait($baseNamespace, string $traitName, $members, ?string $wrapper, string $traitFilename)
    {
        $namespace = new PhpNamespace($baseNamespace);
        $namespace->addUse(ResponseInterface::class);
        $trait = $namespace->addTrait($traitName);

        $body = '$data = new \SimpleXMLElement($response->getContent(false));' . "\n\n// TODO Verify correctness\n";
        if ($wrapper) {
            $body.= "\$data = \$data->$wrapper;\n";
        }
        foreach (\array_keys($members) as $name) {
            $body .= "\$this->$name = \$this->xmlValueOrNull(\$data->$name);\n";
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
    private function buildXmlConfig(string $shapeName): array
    {
        $shape = $this->definition->getShape($shapeName);
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
            $xml = array_merge($xml, $this->buildXmlConfig($data['shape'] ?? $name));
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

    private function addMethodComment(Method $method, array $inputShape, string $baseNamespace): void
    {
        foreach ($inputShape['members'] as $name => $data) {
            $nullable = !\in_array($name, $inputShape['required'] ?? []);
            $param = $this->definition->getShape($data['shape'])['type'];
            if ('structure' === $param) {
                $param = '\\' . $baseNamespace . '\\' . $name . '|array';
            } elseif ('list' === $param) {
                $param = '\\' . $baseNamespace . '\\' . $this->definition->getShape($data['shape'])['member']['shape'] . '[]';
            } else {
                $param = $this->toPhpType($param);
            }

            $method->addComment(sprintf('  %s%s: %s,', $name, $nullable ? '?' : '', $param));
        }
    }

    private function setMethodBody(array $inputShape, Method $method, array $operation, $inputClassName): void
    {
        $body = <<<PHP
\$input = $inputClassName::create(\$input); 

PHP;

        if (isset($inputShape['payload'])) {
            $data = $inputShape['members'][$inputShape['payload']];
            if ($data['streaming'] ?? false) {
                $body .= '$payload = $input->get' . $inputShape['payload'] . '() ?? "";';
            } else {
                // Build XML
                $xml = $this->buildXmlConfig($data['shape']);
                $xml['_root'] = [
                    'type' => $data['shape'],
                    'xmlName' => $data['locationName'],
                    'uri' => $data['xmlNamespace']['uri'] ?? '',
                ];

                $body .= '$xmlConfig = ' . $this->printArray($xml) . ";\n";
                $body .= '$payload = (new XmlBuilder($input->get' . $inputShape['payload'] . '() ?? [], $xmlConfig))->getXml();' . "\n";
            }
            $payloadVariable = '$payload';
        } else {
            // This is a normal body application/x-www-form-urlencoded
            $payloadVariable = '$input->requestBody()';
        }

        $method->setBody(
            $body .
            <<<PHP

\$response = \$this->getResponse(
    '{$operation['http']['method']}', 
    $payloadVariable, 
    \$input->requestHeaders(), 
    \$this->getEndpoint(\$input->requestUri(), \$input->requestQuery())
);

return new {$operation['output']['shape']}(\$response);
PHP
        );
    }
}
