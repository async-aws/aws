<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\RequestSerializer\SerializerProvider;
use AsyncAws\Core\Exception\InvalidArgument;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class InputGenerator
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    /**
     * @var EnumGenerator
     */
    private $enumGenerator;

    /**
     * @var SerializerProvider
     */
    private $serializer;

    /**
     * @var ClassName[]
     */
    private $generated = [];

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($this->namespaceRegistry, $fileWriter);
        $this->serializer = new SerializerProvider($this->namespaceRegistry);
    }

    /**
     * Generate classes for the input. Ie, the request of the API call.
     */
    public function generate(Operation $operation): ClassName
    {
        return $this->generateInputClass($operation, $operation->getInput(), true);
    }

    private function generateInputClass(Operation $operation, StructureShape $shape, bool $root = false): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getInput($shape);

        $namespace = new PhpNamespace($className->getNamespace());
        $class = $namespace->addClass($className->getName());

        $constructorBody = '';

        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            [$returnType, $parameterType, $memberClassName] = $this->typeGenerator->getPhpType($memberShape);
            $nullable = true;
            if ($memberShape instanceof StructureShape) {
                $this->generateInputClass($operation, $memberShape);
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();
                $nullable = false;

                if ($listMemberShape instanceof StructureShape) {
                    $this->generateInputClass($operation, $listMemberShape);
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
                } elseif ($listMemberShape instanceof ListShape || $listMemberShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive ListShape are not yet implemented');
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();
                $nullable = false;

                // Is this a list of objects?
                if ($mapValueShape instanceof StructureShape) {
                    $this->generateInputClass($operation, $mapValueShape);

                    $constructorBody .= strtr('
                        $this->NAME = [];
                        foreach ($input["NAME"] ?? [] as $key => $item) {
                            $this->NAME[$key] = CLASS::create($item);
                        }
                    ', [
                        'NAME' => $member->getName(),
                        'CLASS' => $memberClassName->getName(),
                    ]);
                } elseif ($mapValueShape instanceof ListShape || $mapValueShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive ListShape are not yet implemented');
                } else {
                    // It is a scalar, like a string
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($member->isStreaming()) {
                $parameterType = 'string|resource|callable|iterable';
                $returnType = null;
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            } elseif ('timestamp' === $memberShape->getType()) {
                $constructorBody .= strtr('$this->NAME = !isset($input["NAME"]) ? null : ($input["NAME"] instanceof \DateTimeInterface ? $input["NAME"] : new \DateTimeImmutable($input["NAME"]));' . "\n", ['NAME' => $member->getName()]);
            } else {
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            }

            $property = $class->addProperty($member->getName())->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if (!empty($memberShape->getEnum())) {
                $enumClassName = $this->enumGenerator->generate($memberShape);
                $namespace->addUse($enumClassName->getFqdn());
            }

            if ($member->isRequired()) {
                $property->addComment('@required');
            }
            // the "\n" helps php-cs-fixer to with potential wildcard in parameterType
            $property->addComment("\n@var " . $parameterType . ($nullable ? '|null' : ''));

            $getter = $class->addMethod('get' . $member->getName())
                ->setReturnType($returnType)
                ->setReturnNullable($nullable)
                ->setBody(strtr('return $this->NAME;', ['NAME' => $member->getName()]));

            $setter = $class->addMethod('set' . $member->getName())
                ->setReturnType('self')
                ->setBody(strtr('
                    $this->NAME = $value;
                    return $this;
                ', [
                    'NAME' => $member->getName(),
                ]));
            $setter
                ->addParameter('value')->setType($returnType)->setNullable($nullable)
            ;

            if ($returnType !== $parameterType) {
                $setter->addComment('@param ' . $parameterType . ($nullable ? '|null' : '') . ' $value');
                $getter->addComment('@return ' . $parameterType . ($nullable ? '|null' : ''));
            }
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

            $constructor->addComment($this->typeGenerator->generateDocblock($shape, $className, false, $root));

            $inputParameter = $constructor->addParameter('input')->setType('array');
            if ($root || empty($shape->getRequired())) {
                $inputParameter->setDefaultValue([]);
            }
            $constructor->setBody($constructorBody);
        }
        if ($root) {
            $this->inputClassRequestGetters($shape, $class, $operation);
        }

        // Add validate()
        $namespace->addUse(InvalidArgument::class);
        $validateBody = [];
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            $memberValidate = [];
            $required = $member->isRequired();
            $nullable = true;

            if ($memberShape instanceof StructureShape) {
                $memberValidate[] = \strtr('$this->PROPERTY->validate();', [
                    'PROPERTY' => $member->getName(),
                ]);
            } elseif ($memberShape instanceof ListShape) {
                $nullable = false;
                $listMemberShape = $memberShape->getMember()->getShape();
                $itemValidate = [];
                if ($listMemberShape instanceof StructureShape) {
                    $itemValidate[] = '$item->validate();';
                }
                if (!empty($listMemberShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($listMemberShape);
                    $namespace->addUse($enumClassName->getFqdn());

                    $itemValidate[] = \strtr('if (!ENUMCLASS::exists($item)) {
                        throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" when validating the "%s". The value "%s" is not a valid "ENUMCLASS".\', __CLASS__, $item));
                    }', [
                        'ENUMCLASS' => $enumClassName->getName(),
                        'PROPERTY' => $member->getName(),

                    ]);
                }
                if (!empty($itemValidate)) {
                    $memberValidate[] = \strtr('foreach ($this->PROPERTY as $item) {
                        VALIDATE
                    }', [
                        'VALIDATE' => implode("\n", $itemValidate),
                        'PROPERTY' => $member->getName(),

                    ]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $nullable = false;
                $mapValueShape = $memberShape->getValue()->getShape();
                $itemValidate = [];
                if ($mapValueShape instanceof StructureShape) {
                    $itemValidate[] = '$item->validate();';
                }
                if (!empty($mapValueShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($mapValueShape);
                    $namespace->addUse($enumClassName->getFqdn());

                    $itemValidate[] = \strtr('if (!ENUMCLASS::exists($item)) {
                        throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" when validating the "%s". The value "%s" is not a valid "ENUMCLASS".\', __CLASS__, $item));
                    }', [
                        'ENUMCLASS' => $enumClassName->getName(),
                        'PROPERTY' => $member->getName(),
                    ]);
                }
                if (!empty($itemValidate)) {
                    $memberValidate[] = \strtr('foreach ($this->PROPERTY as $item) {
                        VALIDATE
                    }', [
                        'VALIDATE' => implode("\n", $itemValidate),
                        'PROPERTY' => $member->getName(),

                    ]);
                }
            } else {
                if (!empty($memberShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($memberShape);
                    $namespace->addUse($enumClassName->getFqdn());

                    $memberValidate[] = \strtr('if (!ENUMCLASS::exists($this->PROPERTY)) {
                        throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" when validating the "%s". The value "%s" is not a valid "ENUMCLASS".\', __CLASS__, $this->PROPERTY));
                    }', [
                        'ENUMCLASS' => $enumClassName->getName(),
                        'PROPERTY' => $member->getName(),
                    ]);
                }
            }

            if ($required && $nullable) {
                $validateBody[] = strtr('if (null === $this->PROPERTY) {
                    throw new InvalidArgument(sprintf(\'Missing parameter "PROPERTY" when validating the "%s". The value cannot be null.\', __CLASS__));
                }
                VALIDATE', [
                    'PROPERTY' => $member->getName(),
                    'VALIDATE' => implode("\n\n", $memberValidate),
                ]);
            } elseif (!empty($memberValidate)) {
                if ($nullable) {
                    $validateBody[] = strtr('if (null !== $this->PROPERTY) {
                        VALIDATE
                    }', [
                        'PROPERTY' => $member->getName(),
                        'VALIDATE' => implode("\n\n", $memberValidate),
                    ]);
                } else {
                    $validateBody[] = implode("\n\n", $memberValidate);
                }
            }
        }

        $class->addMethod('validate')->setPublic()->setReturnType('void')->setBody(empty($validateBody) ? '// There are no required properties' : \implode("\n\n", $validateBody));

        $this->fileWriter->write($namespace);

        return $className;
    }

    private function inputClassRequestGetters(StructureShape $inputShape, ClassType $class, Operation $operation): void
    {
        $serializer = $this->serializer->get($operation->getService());
        $body['header'] = '$headers = [\'content-type\' => \'' . $serializer->getContentType() . '\'];' . "\n";
        $body['querystring'] = '$query = [];' . "\n";

        foreach (['header' => '$headers', 'querystring' => '$query'] as $requestPart => $varName) {
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

        if ($operation->hasBody()) {
            if (null !== $payloadProperty = $inputShape->getPayload()) {
                $member = $inputShape->getMember($payloadProperty);
                if ($member->isStreaming()) {
                    $bodyType = null;
                    $body = 'return $this->' . $payloadProperty . ' ?? "";';
                } else {
                    $bodyType = 'string';
                    $body = $serializer->generateForMember($member, $payloadProperty);
                }
            } else {
                $bodyType = 'string';
                $body = $serializer->generateForShape($operation, $inputShape);
            }

            $class->addMethod('requestBody')->setReturnType($bodyType)->setBody($body);
        } else {
            if (null !== $payloadProperty = $inputShape->getPayload()) {
                throw new \LogicException(sprintf('Unexpected body in operation "%s"', $operation->getName()));
            }

            foreach ($inputShape->getMembers() as $member) {
                if (null === $member->getLocation()) {
                    throw new \LogicException(sprintf('Unexpected body in operation "%s"', $operation->getName()));
                }
            }
        }

        $requestUri = null;
        foreach ($inputShape->getMembers() as $member) {
            if ('uri' === $member->getLocation()) {
                if (!isset($requestUri)) {
                    $requestUri = '$uri = [];' . "\n";
                }
                $requestUri .= \strtr('$uri["LOCATION"] = $this->NAME ?? "";', ['NAME' => $member->getName(), 'LOCATION' => $member->getLocationName()]);
            }
        }

        $requestUri = $requestUri ?? '';
        $requestUri .= 'return "' . str_replace(['{', '+}', '}'], ['{$uri[\'', '}', '\']}'], $operation->getHttpRequestUri()) . '";';

        $class->addMethod('requestUri')->setReturnType('string')->setBody($requestUri);
    }
}
