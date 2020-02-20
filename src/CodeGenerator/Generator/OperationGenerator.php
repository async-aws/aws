<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Result;
use AsyncAws\Core\XmlBuilder;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
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
        $this->generateInputClass($service, $operation, $baseNamespace . '\\Input', $inputShape, true);

        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));
        $safeClassName = GeneratorHelper::safeClassName($inputShape->getName());
        $namespace->addUse($baseNamespace . '\\Input\\' . $safeClassName);
        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];

        $class->removeMethod(\lcfirst($operation->getName()));
        $method = $class->addMethod(\lcfirst($operation->getName()));
        if (null !== $documentation = $operation->getDocumentation()) {
            $method->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        if (null !== $documentationUrl = $operation->getDocumentationUrl()) {
            $method->addComment('@see ' . $documentationUrl);
        } elseif (null !== $prefix = $this->definition->getEndpointPrefix()) {
            $method->addComment('@see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-' . $prefix . '-' . $this->definition->getApiVersion() . '.html#' . \strtolower($operation->getName()));
        }

        $comment = GeneratorHelper::addMethodComment($inputShape, $baseNamespace . '\\Input');
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
        if (empty($inputShape->getRequired())) {
            $operationMethodParameter->setDefaultValue([]);
        }

        if (null !== $output = $operation->getOutput()) {
            $outputClass = \sprintf('%s\\Result\\%s', $baseNamespace, GeneratorHelper::safeClassName($output->getName()));
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
    private function generateInputClass(string $service, Operation $operation, string $baseNamespace, StructureShape $inputShape, bool $root = false)
    {
        $members = $inputShape->getMembers();
        $namespace = new PhpNamespace($baseNamespace);
        $class = $namespace->addClass(GeneratorHelper::safeClassName($inputShape->getName()));

        $constructorBody = '';
        $requiredProperties = [];

        foreach ($members as $member) {
            $returnType = null;
            $memberShape = $member->getShape();
            $nullable = true;
            if ($memberShape instanceof StructureShape) {
                $this->generateInputClass($service, $operation, $baseNamespace, $memberShape);
                $memberClassName = GeneratorHelper::safeClassName($memberShape->getName());
                $returnType = $baseNamespace . '\\' . $memberClassName;
                $parameterType = $memberShape->getName();
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? SAFE_CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'SAFE_CLASS' => $memberClassName]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();
                $nullable = false;

                // Is this a list of objects?
                if ($listMemberShape instanceof StructureShape) {
                    $this->generateInputClass($service, $operation, $baseNamespace, $listMemberShape);
                    $listMemberClassName = GeneratorHelper::safeClassName($listMemberShape->getName());

                    $parameterType = $listMemberClassName . '[]';
                    $returnType = $baseNamespace . '\\' . $listMemberClassName;
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return SAFE_CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $member->getName(), 'SAFE_CLASS' => GeneratorHelper::safeClassName($listMemberClassName)]);
                } elseif ($listMemberShape instanceof ListShape || $listMemberShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive ListShape are not yet implemented');
                } else {
                    // It is a scalar, like a string
                    $parameterType = GeneratorHelper::toPhpType($listMemberShape->getType()) . '[]';
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();
                $nullable = false;

                // Is this a list of objects?
                if ($mapValueShape instanceof StructureShape) {
                    $this->generateInputClass($service, $operation, $baseNamespace, $mapValueShape);
                    $mapValueClassName = GeneratorHelper::safeClassName($mapValueShape->getName());

                    $parameterType = $mapValueClassName . '[]';
                    $returnType = $baseNamespace . '\\' . $mapValueClassName;
                    $constructorBody .= strtr('
                        $this->NAME = [];
                        foreach ($input["NAME"] ?? [] as $key => $item) {
                            $this->NAME[$key] = SAFE_CLASS::create($item);
                        }
                    ', [
                        'NAME' => $member->getName(),
                        'SAFE_CLASS' => GeneratorHelper::safeClassName($mapValueClassName),
                    ]);
                } elseif ($mapValueShape instanceof ListShape || $mapValueShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive ListShape are not yet implemented');
                } else {
                    // It is a scalar, like a string
                    $parameterType = GeneratorHelper::toPhpType($mapValueShape->getType()) . '[]';
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($member->isStreaming()) {
                $parameterType = 'string|resource|\Closure';
                $returnType = null;
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            } else {
                $returnType = $parameterType = GeneratorHelper::toPhpType($memberShape->getType());
                if ('\DateTimeImmutable' !== $parameterType) {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
                } else {
                    $constructorBody .= strtr('$this->NAME = !isset($input["NAME"]) ? null : ($input["NAME"] instanceof \DateTimeInterface ? $input["NAME"] : new \DateTimeImmutable($input["NAME"]));' . "\n", ['NAME' => $member->getName()]);
                    $parameterType = $returnType = '\DateTimeInterface';
                }
            }

            $property = $class->addProperty($member->getName())->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if ($member->isRequired()) {
                $requiredProperties[] = $member->getName();
                $property->addComment('@required');
            }
            $property->addComment('@var ' . $parameterType . ($nullable ? '|null' : ''));

            $returnType = '[]' === substr($parameterType, -2) ? 'array' : $returnType;

            $class->addMethod('get' . $member->getName())
                ->setReturnType($returnType)
                ->setReturnNullable($nullable)
                ->setBody(strtr('return $this->NAME;', ['NAME' => $member->getName()]));

            $class->addMethod('set' . $member->getName())
                ->setReturnType('self')
                ->setBody(strtr('
                    $this->NAME = $value;
                    return $this;
                ', [
                    'NAME' => $member->getName(),
                ]))
                ->addParameter('value')->setType($returnType)->setNullable($nullable)
            ;
        }

        // Add named constructor
        $class->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody(strtr('
                return $input instanceof self ? $input : new self(ARGS);
            ', ['ARGS' => empty($constructorBody) ? '' : '$input']))
            ->addParameter('input');

        if (!empty($constructorBody)) {
            $constructor = $class->addMethod('__construct');
            if ($root && null !== $documentationUrl = $operation->getDocumentationUrl()) {
                $constructor->addComment('@see ' . $documentationUrl);
            }

            $constructor->addComment('@param array{');
            foreach (GeneratorHelper::addMethodComment($inputShape, $baseNamespace, $root) as $comment) {
                $constructor->addComment($comment);
            }
            $constructor->addComment('} $input');

            $inputParameter = $constructor->addParameter('input')->setType('array');
            if ($root || empty($inputShape->getRequired())) {
                $inputParameter->setDefaultValue([]);
            }
            $constructor->setBody($constructorBody);
        }
        if ($root) {
            $this->inputClassRequestGetters($inputShape, $class, $operation);
        }

        // Add validate()
        $namespace->addUse(InvalidArgument::class);
        $validateBody = '';

        if (!empty($requiredProperties)) {
            $validateBody = strtr('
                foreach (["PROPERTIES"] as $name) {
                    if (null === $this->$name) {
                        throw new InvalidArgument(sprintf(\'Missing parameter "%s" when validating the "%s". The value cannot be null.\', $name, __CLASS__));
                    }
                }
            ', [
                'PROPERTIES' => implode('", "', $requiredProperties),
            ]);
        }

        foreach ($members as $member) {
            $memberShape = $member->getShape();
            if ($memberShape instanceof StructureShape) {
                $validateBody .= 'if ($this->' . $member->getName() . ') $this->' . $member->getName() . '->validate();' . "\n";
            } elseif (($memberShape instanceof ListShape && $memberShape->getMember()->getShape() instanceof StructureShape) || ($memberShape instanceof MapShape && $memberShape->getValue()->getShape() instanceof StructureShape)) {
                $validateBody .= 'foreach ($this->' . $member->getName() . ' as $item) $item->validate();' . "\n";
            }
        }

        $class->addMethod('validate')->setPublic()->setReturnType('void')->setBody(empty($validateBody) ? '// There are no required properties' : $validateBody);

        $this->fileWriter->write($namespace);
    }

    private function inputClassRequestGetters(StructureShape $inputShape, ClassType $class, Operation $operation): void
    {
        foreach (['header' => '$headers', 'querystring' => '$query', 'payload' => '$payload'] as $requestPart => $varName) {
            $body[$requestPart] = $varName . ' = [];' . "\n";
            if ('payload' === $requestPart) {
                $body[$requestPart] = $varName . " = ['Action' => '{$operation->getName()}', 'Version' => '{$this->definition->getApiVersion()}'];\n";
            }
            foreach ($inputShape->getMembers() as $member) {
                // If location is not specified, it will go in the request body.
                if ($requestPart === ($member->getLocation() ?? 'payload')) {
                    $body[$requestPart] .= 'if ($this->' . $member->getName() . ' !== null) ' . $varName . '["' . ($member->getLocationName() ?? $member->getName()) . '"] = $this->' . $member->getName() . ';' . "\n";
                }
            }

            $body[$requestPart] .= 'return ' . $varName . ';' . "\n";
        }

        $class->addMethod('requestHeaders')->setReturnType('array')->setBody($body['header']);
        $class->addMethod('requestQuery')->setReturnType('array')->setBody($body['querystring']);
        $class->addMethod('requestBody')->setReturnType('array')->setBody($body['payload']);

        foreach ($inputShape->getMembers() as $member) {
            if ('uri' === $member->getLocation()) {
                if (!isset($body['uri'])) {
                    $body['uri'] = '$uri = [];' . "\n";
                }
                $body['uri'] .= \strtr('$uri["LOCATION"] = $this->NAME ?? "";', ['NAME' => $member->getName(), 'LOCATION' => $member->getLocationName()]);
            }
        }

        $body['uri'] = $body['uri'] ?? '';
        $body['uri'] .= 'return "' . str_replace(['{', '+}', '}'], ['{$uri[\'', '}', '\']}'], $operation->getHttpRequestUri()) . '";';

        $class->addMethod('requestUri')->setReturnType('string')->setBody($body['uri']);
    }

    /**
     * Pick only the config from $shapes we are interested in.
     */
    private function buildXmlConfig(Shape $shape): array
    {
        $xml[$shape->getName()] = [
            'type' => $shape->getType(),
        ];
        if ($shape instanceof StructureShape) {
            $members = [];
            foreach ($shape->getMembers() as $member) {
                $memberShape = $member->getShape();
                $members[$member->getName()] = ['shape' => $memberShape->getName()];
                if (null !== $locationName = $member->getLocationName()) {
                    $members[$member->getName()] += ['locationName' => $locationName];
                }
                $xml += $this->buildXmlConfig($memberShape);
            }

            $xml[$shape->getName()]['members'] = $members;
        } elseif ($shape instanceof ListShape) {
            $memberShape = $shape->getMember()->getShape();
            $xml[$shape->getName()]['member'] = ['shape' => $memberShape->getName()];

            $xml += $this->buildXmlConfig($memberShape);
        }

        return $xml;
    }

    private function setMethodBody(StructureShape $inputShape, Method $method, Operation $operation, $inputClassName): void
    {
        $safeInputClassName = GeneratorHelper::safeClassName($inputClassName);
        $body = strtr('
            $input = SAFE_CLASS::create($input);
            $input->validate();
        ', ['SAFE_CLASS' => $safeInputClassName]);

        if (null !== $payloadProperty = $inputShape->getPayload()) {
            $member = $inputShape->getMember($payloadProperty);
            if ($member->isStreaming()) {
                $body .= '$payload = $input->get' . $payloadProperty . '() ?? "";';
            } else {
                // Build XML
                $memberShape = $member->getShape();
                $xml = $this->buildXmlConfig($memberShape);
                $xml['_root'] = [
                    'type' => $memberShape->getName(),
                    'xmlName' => $member->getLocationName(),
                    'uri' => $member->getXmlNamespaceUri(),
                ];

                $body .= '$xmlConfig = ' . GeneratorHelper::printArray($xml) . ";\n";
                $body .= '$payload = (new XmlBuilder($input->requestBody(), $xmlConfig))->getXml();' . "\n";
            }
            $payloadVariable = '$payload';
        } else {
            // This is a normal body application/x-www-form-urlencoded
            $payloadVariable = '$input->requestBody()';
        }

        $params = ['$response', '$this->httpClient'];
        if ((null !== $pagination = $operation->getPagination()) && !empty($pagination->getOutputToken())) {
            $params = \array_merge($params, ['$this', '$input']);
        }
        $param = \implode(', ', $params);

        if (null !== $outputShape = $operation->getOutput()) {
            $safeOutputClassName = GeneratorHelper::safeClassName($outputShape->getName());
            $return = "return new {$safeOutputClassName}($param);";
        } else {
            $return = "return new Result($param);";
        }

        $method->setBody(
            $body .
            <<<PHP

\$response = \$this->getResponse(
    '{$operation->getHttpMethod()}',
    $payloadVariable,
    \$input->requestHeaders(),
    \$this->getEndpoint(\$input->requestUri(), \$input->requestQuery())
);

$return
PHP
        );
    }
}
