<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Result;
use AsyncAws\Core\XmlBuilder;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class OperationGenerator
{
    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * All public classes take a definition as first parameter.
     *
     * @var ServiceDefinition
     */
    private $definition;

    public function __construct(FileWriter $fileWriter, ServiceDefinition $definition)
    {
        $this->fileWriter = $fileWriter;
        $this->definition = $definition;
    }

    /**
     * Update the API client with a new function call.
     */
    public function generate(Operation $operation, string $service, string $baseNamespace): void
    {
        $inputShape = $operation->getInput();
        $this->generateInputClass($service, $apiVersion = $this->definition->getApiVersion(), $operation, $baseNamespace . '\\Input', $inputShape, true);

        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $safeClassName = GeneratorHelper::safeClassName($inputShape->getName());
        $namespace->addUse($baseNamespace . '\\Input\\' . $safeClassName);
        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        if (null !== $prefix = $this->definition->getEndpointPrefix()) {
            if (!$class->hasMethod('getServiceCode')) {
                $class->addMethod('getServiceCode')
                    ->setReturnType('string')
                    ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ;
            }
            $class->getMethod('getServiceCode')
                ->setBody("return '$prefix';");
        }
        if (null !== $signatureVersion = $this->definition->getSignatureVersion()) {
            if (!$class->hasMethod('getSignatureVersion')) {
                $class->addMethod('getSignatureVersion')
                    ->setReturnType('string')
                    ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ;
            }
            $class->getMethod('getSignatureVersion')
                ->setBody("return '$signatureVersion';");
        }

        $class->removeMethod(\lcfirst($operation['name']));
        $method = $class->addMethod(\lcfirst($operation['name']));
        if (null !== $documentation = $this->definition->getOperationDocumentation($operation->getName())) {
            $method->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        if (isset($operation['documentationUrl'])) {
            $method->addComment('@see ' . $operation['documentationUrl']);
        } elseif (null !== $prefix) {
            $method->addComment('@see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-' . $prefix . '-' . $apiVersion . '.html#' . \strtolower($operation['name']));
        }

        $comment = GeneratorHelper::addMethodComment($this->definition, $inputShape, $baseNamespace . '\\Input');
        if (!empty($comment)) {
            $method->addComment('@param array{');
            foreach ($comment as $c) {
                $method->addComment($c);
            }
            $method->addComment('}|' . $safeClassName . ' $input');
        } else {
            // No input array
            $method->addComment('@param array|' . $safeClassName . ' $input');
        }

        $operationMethodParameter = $method->addParameter('input');
        if (empty($inputShape['required'])) {
            $operationMethodParameter->setDefaultValue([]);
        }

        if (isset($operation['output'])) {
            $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, GeneratorHelper::safeClassName($operation['output']['shape']));
            $method->setReturnType($outputClass);
            $namespace->addUse($outputClass);
            $namespace->addUse(XmlBuilder::class);
        } else {
            $method->setReturnType(Result::class);
            $namespace->addUse(Result::class);
        }

        // Generate method body
        $this->setMethodBody($inputShape, $method, $operation, $inputShape->getName());

        $this->fileWriter->write($namespace);
    }

    /**
     * Generate classes for the input.
     */
    private function generateInputClass(string $service, string $apiVersion, Operation $operation, string $baseNamespace, Shape $inputShape, bool $root = false)
    {
        $members = $inputShape['members'];
        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass(GeneratorHelper::safeClassName($inputShape->getName()));

        $constructorBody = '';
        $requiredProperties = [];

        foreach ($members as $name => $data) {
            $returnType = null;
            $parameterType = $data['shape'];
            $memberShape = $this->definition->getShape($parameterType);
            $nullable = true;
            if ('structure' === $memberShape['type']) {
                $this->generateInputClass($service, $apiVersion, $operation, $baseNamespace, $this->definition->getShape($parameterType));
                $returnType = $baseNamespace . '\\' . $parameterType;
                $constructorBody .= sprintf('$this->%s = isset($input["%s"]) ? %s::create($input["%s"]) : null;' . "\n", $name, $name, GeneratorHelper::safeClassName($parameterType), $name);
            } elseif ('list' === $memberShape['type']) {
                $listItemShapeName = $memberShape['member']['shape'];
                $listItemShape = $this->definition->getShape($listItemShapeName);
                $nullable = false;

                // Is this a list of objects?
                if ('structure' === $listItemShape['type']) {
                    $this->generateInputClass($service, $apiVersion, $operation, $baseNamespace, $this->definition->getShape($listItemShapeName));
                    $parameterType = $listItemShapeName . '[]';
                    $returnType = $baseNamespace . '\\' . $listItemShapeName;
                    $constructorBody .= sprintf('$this->%s = array_map(function($item) { return %s::create($item); }, $input["%s"] ?? []);' . "\n", $name, GeneratorHelper::safeClassName(
                        $listItemShapeName
                    ), $name);
                } else {
                    // It is a scalar, like a string
                    $parameterType = $listItemShape['type'] . '[]';
                    $constructorBody .= sprintf('$this->%s = $input["%s"] ?? [];' . "\n", $name, $name);
                }
            } elseif ($data['streaming'] ?? false) {
                $parameterType = 'string|resource|\Closure';
                $returnType = null;
                $constructorBody .= sprintf('$this->%s = $input["%s"] ?? null;' . "\n", $name, $name);
            } else {
                $returnType = $parameterType = GeneratorHelper::toPhpType($memberShape['type']);
                if ('\DateTimeImmutable' !== $parameterType) {
                    $constructorBody .= sprintf('$this->%s = $input["%s"] ?? null;' . "\n", $name, $name);
                } else {
                    $constructorBody .= sprintf('$this->%s = !isset($input["%s"]) ? null : ($input["%s"] instanceof \DateTimeInterface ? $input["%s"] : new \DateTimeImmutable($input["%s"]));' . "\n", $name, $name, $name, $name, $name);
                    $parameterType = $returnType = '\DateTimeInterface';
                }
            }

            $property = $class->addProperty($name)->setPrivate();
            if (null !== $propertyDocumentation = $this->definition->getParameterDocumentation($inputShape->getName(), $name, $data['shape'])) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if (\in_array($name, $inputShape['required'] ?? [])) {
                $requiredProperties[] = $name;
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

        // Add named constructor
        $selfParameter = empty($constructorBody) ? '' : '$input';
        $class->addMethod('create')->setStatic(true)->setReturnType('self')->setBody(
            <<<PHP
return \$input instanceof self ? \$input : new self($selfParameter);
PHP
        )->addParameter('input');

        if (!empty($constructorBody)) {
            $constructor = $class->addMethod('__construct');
            if ($root && isset($operation['documentationUrl'])) {
                $constructor->addComment('@see ' . $operation['documentationUrl']);
            }

            $constructor->addComment('@param array{');
            foreach (GeneratorHelper::addMethodComment($this->definition, $inputShape, $baseNamespace, $root) as $comment) {
                $constructor->addComment($comment);
            }
            $constructor->addComment('} $input');

            $inputParameter = $constructor->addParameter('input')->setType('array');
            if (empty($inputShape['required']) || $root) {
                $inputParameter->setDefaultValue([]);
            }
            $constructor->setBody($constructorBody);
        }
        if ($root) {
            $this->inputClassRequestGetters($inputShape, $class, $operation->getName(), $apiVersion);
        }

        // Add validate()
        $namespace->addUse(InvalidArgument::class);
        $validateBody = '';

        if (!empty($requiredProperties)) {
            $requiredArray = '\'' . implode("', '", $requiredProperties) . '\'';

            $validateBody = <<<PHP
foreach ([$requiredArray] as \$name) {
    if (null === \$this->\$name) {
        throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', \$name, __CLASS__));
    }
}

PHP;
        }

        foreach ($members as $name => $data) {
            $memberShape = $this->definition->getShape($data['shape']);
            $type = $memberShape['type'] ?? null;
            if ('structure' === $type) {
                $validateBody .= 'if ($this->' . $name . ') $this->' . $name . '->validate();' . "\n";
            } elseif ('list' === $type) {
                $listItemShapeName = $memberShape['member']['shape'];
                // is the list item an object?
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    $validateBody .= 'foreach ($this->' . $name . ' as $item) $item->validate();' . "\n";
                }
            }
        }

        $class->addMethod('validate')->setPublic()->setReturnType('void')->setBody(empty($validateBody) ? '// There are no required properties' : $validateBody);

        $this->fileWriter->write($namespace);
    }

    private function inputClassRequestGetters(Shape $inputShape, ClassType $class, string $operation, string $apiVersion): void
    {
        foreach (['header' => '$headers', 'querystring' => '$query', 'payload' => '$payload'] as $requestPart => $varName) {
            $body[$requestPart] = $varName . ' = [];' . "\n";
            if ('payload' === $requestPart) {
                $body[$requestPart] = $varName . " = ['Action' => '$operation', 'Version' => '$apiVersion'];\n";
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

    /**
     * Pick only the config from $shapes we are interested in.
     */
    private function buildXmlConfig(string $shapeName): array
    {
        $shape = $this->definition->getShape($shapeName);
        if (!\in_array($shape['type'] ?? 'structure', ['structure', 'list'])) {
            $xml[$shapeName]['type'] = $shape['type'];

            return $xml;
        }

        $xml[$shapeName] = $shape->toArray();
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

    private function setMethodBody(Shape $inputShape, Method $method, Operation $operation, $inputClassName): void
    {
        $safeInputClassName = GeneratorHelper::safeClassName($inputClassName);
        $body = <<<PHP
\$input = $safeInputClassName::create(\$input);
\$input->validate();

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

                $body .= '$xmlConfig = ' . GeneratorHelper::printArray($xml) . ";\n";
                $body .= '$payload = (new XmlBuilder($input->requestBody(), $xmlConfig))->getXml();' . "\n";
            }
            $payloadVariable = '$payload';
        } else {
            // This is a normal body application/x-www-form-urlencoded
            $payloadVariable = '$input->requestBody()';
        }

        $param = '';
        if ($this->operationRequiresHttpClient($operation['name'])) {
            $param = ', $this->httpClient';
        }

        if (isset($operation['output'])) {
            $safeOutputClassName = GeneratorHelper::safeClassName($operation['output']['shape']);
            $return = "return new {$safeOutputClassName}(\$response$param);";
        } else {
            $return = "return new Result(\$response$param);";
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

$return
PHP
        );
    }

    private function operationRequiresHttpClient(string $operationName): bool
    {
        $operation = $this->definition->getOperation($operationName);

        if (!isset($operation['output'])) {
            return false;
        }
        // Check if output has streamable body
        $outputShape = $this->definition->getShape($operation['output']['shape']);
        $payload = $outputShape['payload'] ?? null;
        if (null !== $payload && ($outputShape['members'][$payload]['streaming'] ?? false)) {
            return true;
        }

        // TODO check if pagination is supported

        return false;
    }
}
