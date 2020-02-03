<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Result;
use AsyncAws\Core\StreamableBody;
use AsyncAws\Core\XmlBuilder;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ApiGenerator
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

    public function __construct(string $srcDirectory)
    {
        $this->fileWriter = new FileWriter($srcDirectory);
    }

    /**
     * Update the API client with a new function call.
     */
    public function generateOperation(ServiceDefinition $definition, string $operationName, string $service, string $baseNamespace): void
    {
        $this->definition = $definition;
        $operation = $definition->getOperation($operationName);
        $inputShape = $definition->getShape($operation['input']['shape']) ?? [];

        $inputClassName = $operation['input']['shape'];
        $this->generateInputClass($service, $apiVersion = $definition->getApiVersion(), $operationName, $baseNamespace . '\\Input', $inputClassName, true);

        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $safeClassName = $this->safeClassName($inputClassName);
        $namespace->addUse($baseNamespace . '\\Input\\' . $safeClassName);
        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        if (null !== $prefix = $definition->getEndpointPrefix()) {
            if (!$class->hasMethod('getServiceCode')) {
                $class->addMethod('getServiceCode')
                    ->setReturnType('string')
                    ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ;
            }
            $class->getMethod('getServiceCode')
                ->setBody("return '$prefix';");
        }
        if (null !== $signatureVersion = $definition->getSignatureVersion()) {
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
        if (isset($operation['documentationUrl'])) {
            $method->addComment('@see ' . $operation['documentationUrl']);
        } elseif (null !== $prefix) {
            $method->addComment('@see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-' . $prefix . '-' . $apiVersion . '.html#' . \strtolower($operation['name']));
        }

        $method->addComment('@param array{');
        $this->addMethodComment($method, $inputShape, $baseNamespace . '\\Input');
        $method->addComment('}|' . $safeClassName . ' $input');
        $operationMethodParameter = $method->addParameter('input');
        if (empty($this->definition->getShape($inputClassName)['required'])) {
            $operationMethodParameter->setDefaultValue([]);
        }

        $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, $this->safeClassName($operation['output']['shape']));
        $method->setReturnType($outputClass);
        $namespace->addUse($outputClass);
        $namespace->addUse(XmlBuilder::class);

        // Generate method body
        $this->setMethodBody($inputShape, $method, $operation, $inputClassName);

        $this->fileWriter->write($namespace);
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generateResultClass(ServiceDefinition $definition, string $operationName, string $baseNamespace, string $className, bool $root, bool $useTrait)
    {
        $this->definition = $definition;
        $this->doGenerateResultClass($baseNamespace, $className, $root, $useTrait, $operationName);
    }

    public function generateOutputTrait(ServiceDefinition $definition, string $operationName, string $baseNamespace, string $className)
    {
        $this->definition = $definition;
        $traitName = $className . 'Trait';

        $namespace = new PhpNamespace($baseNamespace);
        $trait = $namespace->addTrait($traitName);

        $namespace->addUse(ResponseInterface::class);
        $namespace->addUse(HttpClientInterface::class);

        $isNew = !trait_exists($baseNamespace . '\\' . $traitName);
        $this->resultClassPopulateResult($operationName, $className, $isNew, $namespace, $trait);

        $this->fileWriter->write($namespace);
    }

    private function doGenerateResultClass(string $baseNamespace, string $className, bool $root = false, ?bool $useTrait = null, ?string $operationName = null): void
    {
        $inputShape = $this->definition->getShape($className);

        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass($this->safeClassName($className));

        if ($root) {
            $namespace->addUse(Result::class);
            $class->addExtend(Result::class);

            $traitName = $baseNamespace . '\\' . $className . 'Trait';
            if ($useTrait) {
                $class->addTrait($traitName);
            } else {
                $namespace->addUse(ResponseInterface::class);
                $namespace->addUse(HttpClientInterface::class);
                $this->resultClassPopulateResult($operationName, $className, false, $namespace, $class);
                $this->fileWriter->delete($traitName);
            }
        } else {
            // Named constructor
            $this->resultClassAddNamedConstructor($baseNamespace, $inputShape, $class);
        }

        $this->resultClassAddProperties($baseNamespace, $root, $inputShape, $class, $namespace);

        $this->fileWriter->write($namespace);
    }

    /**
     * Generate classes for the input.
     */
    private function generateInputClass(string $service, string $apiVersion, string $operationName, string $baseNamespace, string $className, bool $root = false)
    {
        $operation = $this->definition->getOperation($operationName);
        $shapes = $this->definition->getShapes();
        $inputShape = $shapes[$className] ?? [];
        $members = $inputShape['members'];

        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass($this->safeClassName($className));
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
        $requiredProperties = [];

        foreach ($members as $name => $data) {
            $parameterType = $members[$name]['shape'];
            $memberShape = $shapes[$parameterType];
            $nullable = true;
            if ('structure' === $memberShape['type']) {
                $this->generateInputClass($service, $apiVersion, $operationName, $baseNamespace, $parameterType);
                $returnType = $baseNamespace . '\\' . $parameterType;
                $constructorBody .= sprintf('$this->%s = isset($input["%s"]) ? %s::create($input["%s"]) : null;' . "\n", $name, $name, $this->safeClassName($parameterType), $name);
            } elseif ('list' === $memberShape['type']) {
                $listItemShapeName = $memberShape['member']['shape'];
                $listItemShape = $this->definition->getShape($listItemShapeName);
                $nullable = false;

                // Is this a list of objects?
                if ('structure' === $listItemShape['type']) {
                    $this->generateInputClass($service, $apiVersion, $operationName, $baseNamespace, $listItemShapeName);
                    $parameterType = $listItemShapeName . '[]';
                    $returnType = $baseNamespace . '\\' . $listItemShapeName;
                    $constructorBody .= sprintf('$this->%s = array_map(function($item) { return %s::create($item); }, $input["%s"] ?? []);' . "\n", $name, $this->safeClassName(
                        $listItemShapeName
                    ), $name);
                } else {
                    // It is a scalar, like a string
                    $parameterType = $listItemShape['type'] . '[]';
                    $constructorBody .= sprintf('$this->%s = $input["%s"] ?? [];' . "\n", $name, $name);
                }
            } else {
                $returnType = $parameterType = $this->toPhpType($memberShape['type']);
                if ('\DateTimeImmutable' !== $parameterType) {
                    $constructorBody .= sprintf('$this->%s = $input["%s"] ?? null;' . "\n", $name, $name);
                } else {
                    $constructorBody .= sprintf('$this->%s = !isset($input["%s"]) ? null : ($input["%s"] instanceof \DateTimeImmutable ? $input["%s"] : new \DateTimeImmutable($input["%s"]));' . "\n", $name, $name, $name, $name, $name);
                }
            }

            $property = $class->addProperty($name)->setPrivate();
            if (\in_array($name, $shapes[$className]['required'] ?? [])) {
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

        $constructor->setBody($constructorBody);
        if ($root) {
            $this->inputClassRequestGetters($inputShape, $class, $operationName, $apiVersion);
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
            $memberShape = $shapes[$data['shape']];
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

    private function inputClassRequestGetters(array $inputShape, ClassType $class, string $operation, string $apiVersion): void
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

    private function parseXmlResponse(?string $rootObjectName, string $rootObjectShape, string $prefix): string
    {
        $shape = $this->definition->getShape($rootObjectShape);
        if (!\in_array($shape['type'], ['structure', 'list'])) {
            $type = $this->toPhpType($shape['type']);

            return "\$this->$rootObjectName = \$this->xmlValueOrNull(\$data->$rootObjectName, '$type');\n";
        }

        if ('list' === $shape['type']) {
            $childCall = $this->parseXmlResponse(null, $shape['member']['shape'], '$item');

            return <<<PHP
\$this->$rootObjectName = [];
foreach ($prefix->$rootObjectName as \$item) {
    \$this->{$rootObjectName}[] = $childCall;
}

PHP;
        }

        // Assert: $shape['type'] === 'structure'
        $safeRootObjectShape = $this->safeClassName($rootObjectShape);
        $body = "new $safeRootObjectShape([\n";
        foreach ($shape['members'] as $name => $memberData) {
            $memberShape = $this->definition->getShape($memberData['shape']);
            if ('structure' === $memberShape['type']) {
                $body .= "'$name' => " . $this->parseXmlResponse(null, $name, $prefix . '->' . $name) . ",\n";
            } elseif ('list' === $memberShape['type']) {
                // TODO check for list type
                // TODO we should do something similar like above, but here we are in the middle of defining an array.
                throw new \RuntimeException('This si not implemented yet');
            //$body .= sprintf('$this->%s = array_map(function($item) { return %s::create($item); }, $input["%s"] ?? []);' . "\n", $name, $this->safeClassName($memberShape['member']['shape']), $name);
            } else {
                $type = $this->toPhpType($memberShape['type']);
                $body .= "'$name' => \$this->xmlValueOrNull($prefix->$name, '$type'),\n";
            }
        }
        $body .= '])';
        if ($rootObjectName) {
            $body = "\$this->$rootObjectName = $body;\n";
        }

        return $body;
    }

    private function toPhpType(?string $parameterType): string
    {
        if ('boolean' === $parameterType) {
            $parameterType = 'bool';
        } elseif (\in_array($parameterType, ['integer'])) {
            $parameterType = 'int';
        } elseif (\in_array($parameterType, ['blob', 'long'])) {
            $parameterType = 'string';
        } elseif (\in_array($parameterType, ['map', 'list'])) {
            $parameterType = 'array';
        } elseif (\in_array($parameterType, ['timestamp'])) {
            $parameterType = '\DateTimeImmutable';
        }

        return $parameterType;
    }

    /**
     * Make sure we dont use a class name like Trait or Object.
     */
    private function safeClassName(string $name): string
    {
        if (\in_array($name, ['Object', 'Class', 'Trait'])) {
            return 'Aws' . $name;
        }

        return $name;
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

        $xml[$shapeName] = $shape;
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

    /**
     * This is will produce the same result as `var_export` but on only one line.
     */
    private function printArray(array $data): string
    {
        $output = '[';
        foreach ($data as $name => $value) {
            $output .= sprintf('%s => %s,', (\is_int($name) ? $name : '"' . $name . '"'), \is_array($value) ? $this->printArray($value) : ("'" . $value . "'"));
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
                $listItemShapeName = $this->definition->getShape($data['shape'])['member']['shape'];

                // is the list item an object?
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    $param = '\\' . $baseNamespace . '\\' . $listItemShapeName . '[]';
                } else {
                    $param = $type . '[]';
                }
            } elseif ('timestamp' === $param) {
                $param = '\DateTimeImmutable|string';
            } else {
                $param = $this->toPhpType($param);
            }

            $method->addComment(sprintf('  %s%s: %s,', $name, $nullable ? '?' : '', $param));
        }
    }

    private function setMethodBody(array $inputShape, Method $method, array $operation, $inputClassName): void
    {
        $safeInputClassName = $this->safeClassName($inputClassName);
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

                $body .= '$xmlConfig = ' . $this->printArray($xml) . ";\n";
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

        $method->setBody(
            $body .
            <<<PHP

\$response = \$this->getResponse(
    '{$operation['http']['method']}',
    $payloadVariable,
    \$input->requestHeaders(),
    \$this->getEndpoint(\$input->requestUri(), \$input->requestQuery())
);

return new {$this->safeClassName($operation['output']['shape'])}(\$response$param);
PHP
        );
    }

    private function operationRequiresHttpClient(string $operationName): bool
    {
        $operation = $this->definition->getOperation($operationName);

        // Check if output has streamable body
        $outputShape = $this->definition->getShape($operation['output']['shape']);
        $payload = $outputShape['payload'] ?? null;
        if (null !== $payload && ($outputShape['members'][$payload]['streaming'] ?? false)) {
            return true;
        }

        // TODO check if pagination is supported

        return false;
    }

    private function resultClassAddNamedConstructor(string $baseNamespace, array $inputShape, ClassType $class): void
    {
        $class->addMethod('create')->setStatic(true)->setReturnType('self')->setBody(
            <<<PHP
return \$input instanceof self ? \$input : new self(\$input);
PHP
        )->addParameter('input');

        // We need a constructor
        $constructor = $class->addMethod('__construct');
        $constructor->addComment('@param array{');
        $this->addMethodComment($constructor, $inputShape, $baseNamespace);
        $constructor->addComment('} $input');
        $constructor->addParameter('input')->setType('array')->setDefaultValue([]);

        $constructorBody = '';
        foreach ($inputShape['members'] as $name => $data) {
            $parameterType = $data['shape'];
            $memberShape = $this->definition->getShape($parameterType);
            if ('structure' === $memberShape['type']) {
                $this->doGenerateResultClass($baseNamespace, $parameterType);
                $constructorBody .= sprintf('$this->%s = isset($input["%s"]) ? %s::create($input["%s"]) : null;' . "\n", $name, $name, $this->safeClassName($parameterType), $name);
            } elseif ('list' === $memberShape['type']) {
                // Check if this is a list of objects
                $listItemShapeName = $memberShape['member']['shape'];
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    $this->doGenerateResultClass($baseNamespace, $listItemShapeName);
                    $constructorBody .= sprintf('$this->%s = array_map(function($item) { return %s::create($item); }, $input["%s"] ?? []);' . "\n", $name, $this->safeClassName($listItemShapeName), $name);
                } else {
                    $constructorBody .= sprintf('$this->%s = $input["%s"] ?? [];' . "\n", $name, $name);
                }
            } else {
                $constructorBody .= sprintf('$this->%s = $input["%s"] ?? null;' . "\n", $name, $name);
            }
        }
        $constructor->setBody($constructorBody);
    }

    /**
     * Add properties and getters.
     */
    private function resultClassAddProperties(string $baseNamespace, bool $root, ?array $inputShape, ClassType $class, PhpNamespace $namespace): void
    {
        $members = $inputShape['members'];
        foreach ($members as $name => $data) {
            $nullable = $returnType = null;
            $property = $class->addProperty($name)->setPrivate();
            $parameterType = $members[$name]['shape'];
            $memberShape = $this->definition->getShape($parameterType);

            if ('structure' === $memberShape['type']) {
                $this->doGenerateResultClass($baseNamespace, $parameterType);
                $parameterType = $baseNamespace . '\\' . $this->safeClassName($parameterType);
            } elseif ('list' === $memberShape['type']) {
                $parameterType = 'array';
                $nullable = false;
                $property->setValue([]);

                // Check if this is a list of objects
                $listItemShapeName = $memberShape['member']['shape'];
                $type = $this->definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    $this->doGenerateResultClass($baseNamespace, $listItemShapeName);
                    $returnType = $this->safeClassName($listItemShapeName);
                } else {
                    $returnType = $type . '[]';
                }
            } elseif ($data['streaming'] ?? false) {
                $parameterType = StreamableBody::class;
                $namespace->addUse(StreamableBody::class);
                $nullable = false;
            } else {
                $parameterType = $this->toPhpType($memberShape['type']);
            }

            $callInitialize = '';
            if ($root) {
                $callInitialize = <<<PHP
\$this->initialize();
PHP;
            }

            $method = $class->addMethod('get' . $name)
                ->setReturnType($parameterType)
                ->setBody(
                    <<<PHP
$callInitialize
return \$this->{$name};
PHP
                );

            $nullable = $nullable ?? !\in_array($name, $inputShape['required'] ?? []);
            if ($returnType) {
                $method->addComment('@return ' . $returnType . ('array' === $parameterType ? '[]' : ''));
            }
            $method->setReturnNullable($nullable);
        }
    }

    private function resultClassPopulateResult(string $operationName, string $className, bool $isNew, PhpNamespace $namespace, ClassType $class): void
    {
        $shape = $this->definition->getShape($className);

        // Parse headers
        $nonHeaders = [];
        $body = '';
        foreach ($shape['members'] as $name => $member) {
            if (($member['location'] ?? null) !== 'header') {
                $nonHeaders[$name] = $member;

                continue;
            }
            $locationName = $member['locationName'] ?? $name;
            $body .= "\$this->$name = \$headers['{$locationName}'];\n";
        }

        $comment = '';
        if ($isNew) {
            $comment = "// TODO Verify correctness\n";
        }

        // Prepend with $headers = ...
        if (!empty($body)) {
            $body = <<<PHP
\$headers = \$response->getHeaders(false);

$comment
PHP
                . $body;
        }

        $body .= "\n";
        $xmlParser = '';
        if (isset($shape['payload'])) {
            $name = $shape['payload'];
            $member = $shape['members'][$name];
            if (true === ($member['streaming'] ?? false)) {
                // Make sure we can stream this.
                $namespace->addUse(StreamableBody::class);
                $body .= <<<PHP
if (null !== \$httpClient) {
    \$this->$name = new StreamableBody(\$httpClient->stream(\$response));
} else {
    \$this->$name = \$response->getContent(false);
}

PHP;
            } else {
                $xmlParser .= "\n\n" . $this->parseXmlResponse($name, $member['shape'], '$data');
            }
        } else {
            // All remaining members are in the body
            foreach ($nonHeaders as $name => $member) {
                if (($member['location'] ?? null) === 'headers') {
                    // There is a difference between 'header' and 'headers'
                    continue;
                }
                $xmlParser .= $this->parseXmlResponse($name, $member['shape'], '$data');
            }
        }

        if (!empty($xmlParser)) {
            $body .= "\$data = new \SimpleXMLElement(\$response->getContent(false));";
            $wrapper = $this->definition->getOperation($operationName)['output']['resultWrapper'] ?? null;
            if (null !== $wrapper) {
                $body .= "\$data = \$data->$wrapper;\n";
            }
            $body .= "\n" . $xmlParser;
        }

        $method = $class->addMethod('populateResult')
            ->setReturnType('void')
            ->setProtected()
            ->setBody($body);
        $method->addParameter('response')->setType(ResponseInterface::class);
        $method->addParameter('httpClient')->setType(HttpClientInterface::class)->setNullable(true);
    }
}
