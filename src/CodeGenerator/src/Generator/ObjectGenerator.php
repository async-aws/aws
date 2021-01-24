<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\RequestSerializer\SerializerProvider;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Stream\ResultStream;
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
class ObjectGenerator
{
    /**
     * @var ClassName[]
     */
    private $generated = [];

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

    private $managedMethods;

    private $usedShapedInput;

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter, array $managedMethods, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($this->namespaceRegistry, $fileWriter);
        $this->serializer = new SerializerProvider($this->namespaceRegistry);
        $this->managedMethods = $managedMethods;
    }

    public function generate(StructureShape $shape): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getObject($shape);

        $namespace = new PhpNamespace($className->getNamespace());
        $class = $namespace->addClass($className->getName());
        $class->setFinal();
        if (null !== $documentation = $shape->getDocumentation()) {
            $class->addComment(GeneratorHelper::parseDocumentation($documentation, false));
        }

        // Named constructor
        $this->namedConstructor($shape, $class, $namespace);
        $this->addProperties($shape, $class, $namespace);

        $serializer = $this->serializer->get($shape->getService());
        if ($this->isShapeUsedInput($shape)) {
            [$returnType, $requestBody, $args] = $serializer->generateRequestBuilder($shape) + [null, null, []];
            $method = $class->addMethod('requestBody')->setReturnType($returnType)->setBody($requestBody)->setPublic()->setComment('@internal');
            foreach ($args as $arg => $type) {
                $method->addParameter($arg)->setType($type);
            }
        }

        $namespace->addUse(InvalidArgument::class);
        $this->fileWriter->write($namespace);

        return $className;
    }

    private function isShapeUsedInput(StructureShape $shape): bool
    {
        if (null === $this->usedShapedInput) {
            $service = $shape->getService();
            $walk = function (Shape $shape) use (&$walk) {
                if (isset($this->usedShapedInput[$shape->getName()])) {
                    // Node already visited
                    return;
                }

                $this->usedShapedInput[$shape->getName()] = true;
                if ($shape instanceof StructureShape) {
                    foreach ($shape->getMembers() as $member) {
                        $walk($member->getShape());
                    }
                } elseif ($shape instanceof ListShape) {
                    $walk($shape->getMember()->getShape());
                } elseif ($shape instanceof MapShape) {
                    $walk($shape->getValue()->getShape());
                }
            };

            foreach ($this->managedMethods as $method) {
                if (null !== $operation = $service->getOperation($method)) {
                    $walk($operation->getInput());
                }
                if (null !== $waiter = $service->getWaiter($method)) {
                    $walk($waiter->getOperation()->getInput());
                }
            }
        }

        return $this->usedShapedInput[$shape->getName()] ?? false;
    }

    private function namedConstructor(StructureShape $shape, ClassType $class, PhpNamespace $namespace): void
    {
        $class->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody('return $input instanceof self ? $input : new self($input);')
            ->addParameter('input');

        // We need a constructor
        $constructor = $class->addMethod('__construct');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], false, false, true);
        $constructor->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $namespace->addUse($memberClassName->getFqdn());
        }

        $constructor->addParameter('input')->setType('array');

        $constructorBody = '';
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            if ($memberShape instanceof StructureShape) {
                $objectClass = $this->generate($memberShape);
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // Check if this is a list of objects
                if ($listMemberShape instanceof StructureShape) {
                    $objectClass = $this->generate($listMemberShape);
                    $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? array_map([CLASS::class, "create"], $input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                if ($mapValueShape instanceof StructureShape) {
                    $objectClass = $this->generate($mapValueShape);
                    $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? array_map([CLASS::class, "create"], $input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
                }
            } else {
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            }
        }
        $constructor->setBody($constructorBody);
    }

    /**
     * Add properties and getters.
     */
    private function addProperties(StructureShape $shape, ClassType $class, PhpNamespace $namespace): void
    {
        foreach ($shape->getMembers() as $member) {
            $nullable = $returnType = null;
            $memberShape = $member->getShape();
            $property = $class->addProperty($member->getName())->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->setComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($memberShape);
            foreach ($memberClassNames as $memberClassName) {
                $namespace->addUse($memberClassName->getFqdn());
            }

            if (!empty($memberShape->getEnum())) {
                $enumClassName = $this->enumGenerator->generate($memberShape);
                $namespace->addUse($enumClassName->getFqdn());
            }
            $getterSetterNullable = true;

            if ($memberShape instanceof StructureShape) {
                $this->generate($memberShape);
            } elseif ($memberShape instanceof MapShape) {
                $nullable = $getterSetterNullable = false;
                $mapKeyShape = $memberShape->getKey()->getShape();
                if ('string' !== $mapKeyShape->getType()) {
                    throw new \RuntimeException('Complex maps are not supported');
                }
                if (!empty($mapKeyShape->getEnum())) {
                    $this->enumGenerator->generate($mapKeyShape);
                }

                if (($valueShape = $memberShape->getValue()->getShape()) instanceof StructureShape) {
                    $this->generate($valueShape);
                }
                if (!empty($valueShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($valueShape);
                    $namespace->addUse($enumClassName->getFqdn());
                }
            } elseif ($memberShape instanceof ListShape) {
                $nullable = $getterSetterNullable = false;
                $memberShape->getMember()->getShape();

                if (($memberShape = $memberShape->getMember()->getShape()) instanceof StructureShape) {
                    $this->generate($memberShape);
                }
                if (!empty($memberShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($memberShape);
                    $namespace->addUse($enumClassName->getFqdn());
                }
            } elseif ($member->isStreaming()) {
                $returnType = ResultStream::class;
                $parameterType = ResultStream::class;
                $namespace->addUse(ResultStream::class);
                $nullable = false;
            }

            $method = $class->addMethod('get' . \ucfirst($member->getName()))
                ->setReturnType($returnType);

            $deprecation = '';
            if ($member->isDeprecated()) {
                $method->addComment('@deprecated');
                $deprecation = strtr('@trigger_error(\sprintf(\'The property "NAME" of "%s" is deprecated by AWS.\', __CLASS__), E_USER_DEPRECATED);', ['NAME' => $member->getName()]);
            }

            if ($getterSetterNullable) {
                $method->setBody($deprecation . strtr('
                        return $this->NAME;
                    ', [
                    'NAME' => $member->getName(),
                ]));
            } else {
                $method->setBody($deprecation . strtr('
                        return $this->NAME ?? [];
                    ', [
                    'NAME' => $member->getName(),
                ]));
            }

            $nullable = $nullable ?? !$member->isRequired();
            if ($parameterType && $parameterType !== $returnType && (empty($memberClassNames) || $memberClassNames[0]->getName() !== $parameterType)) {
                $method->addComment('@return ' . $parameterType . ($nullable ? '|null' : ''));
            }
            $method->setReturnNullable($nullable);
        }
    }
}
