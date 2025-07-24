<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\CodeGenerator\Generator\RequestSerializer\SerializerProvider;
use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Stream\ResultStream;

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
     * @var ClassRegistry
     */
    private $classRegistry;

    /**
     * @var ClassName[]
     */
    private $generated = [];

    /**
     * @var bool[]
     */
    private $generatedIsStrictEnum = [];

    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

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
     * @var list<string>
     */
    private $managedMethods;

    /**
     * @var array<string, bool>|null
     */
    private $usedShapedInput;

    /**
     * @param list<string> $managedMethods
     */
    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry, array $managedMethods, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($this->classRegistry, $this->namespaceRegistry);
        $this->serializer = new SerializerProvider($this->namespaceRegistry, $requirementsRegistry);
        $this->managedMethods = $managedMethods;
    }

    public function generate(StructureShape $shape, bool $forEndpoint = false, bool $strictEnum = true): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            if ($strictEnum || false === $this->generatedIsStrictEnum[$shape->getName()]) {
                return $this->generated[$shape->getName()];
            }
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getObject($shape);
        $this->generatedIsStrictEnum[$shape->getName()] = $strictEnum;

        $classBuilder = $this->classRegistry->register($className->getFqdn());
        $classBuilder->setFinal();
        $classBuilder->removeComment();
        if (null !== $documentation = $shape->getDocumentationMain()) {
            $classBuilder->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        // Named constructor
        $this->namedConstructor($shape, $classBuilder, $strictEnum);
        $this->addProperties($shape, $classBuilder, $forEndpoint, $strictEnum);

        if ($forEndpoint) {
            $classBuilder->addUse(EndpointInterface::class);
            $classBuilder->addImplement(EndpointInterface::class);
        }

        $serializer = $this->serializer->get($shape->getService());
        if ($this->isShapeUsedInput($shape)) {
            $serializerBuilderResult = $serializer->generateRequestBuilder($shape, false);
            foreach ($serializerBuilderResult->getUsedClasses() as $classNameFqdn) {
                $classBuilder->addUse($classNameFqdn);
            }

            $method = $classBuilder->addMethod('requestBody')->setReturnType($serializerBuilderResult->getReturnType())->setBody($serializerBuilderResult->getBody())->setPublic()->setComment('@internal');
            foreach ($serializerBuilderResult->getExtraMethodArgs() as $arg => $type) {
                $method->addParameter($arg)->setType($type);
            }
        }

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

    private function namedConstructor(StructureShape $shape, ClassBuilder $classBuilder, bool $strictEnum): void
    {
        if (empty($shape->getMembers())) {
            $createMethod = $classBuilder->addMethod('create')
                ->setStatic(true)
                ->setReturnType('self')
                ->setBody('return $input instanceof self ? $input : new self();');
            $createMethod->addParameter('input');
            [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], true, false, true, [], $strictEnum);
            $createMethod->addComment($doc);
            foreach ($memberClassNames as $memberClassName) {
                $classBuilder->addUse($memberClassName->getFqdn());
            }

            return;
        }

        $createMethod = $classBuilder->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody('return $input instanceof self ? $input : new self($input);');
        $createMethod->addParameter('input');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], true, false, true, [], $strictEnum);
        $createMethod->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }

        // We need a constructor
        $constructor = $classBuilder->addMethod('__construct');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], false, false, true, [], $strictEnum);
        $constructor->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }

        $constructor->addParameter('input')->setType('array');

        // To throw an exception in an expression, we need to use a method to support PHP 7.x
        $needsThrowMethod = false;

        $constructorBody = '';
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            if ($memberShape instanceof StructureShape) {
                $objectClass = $this->generate($memberShape, false, $strictEnum);
                $memberCode = strtr('CLASS::create($input["NAME"])', ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // Check if this is a list of objects
                if ($listMemberShape instanceof StructureShape) {
                    $objectClass = $this->generate($listMemberShape, false, $strictEnum);
                    $memberCode = strtr('array_map([CLASS::class, "create"], $input["NAME"])', ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
                } else {
                    $memberCode = strtr('$input["NAME"]', ['NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                if ($mapValueShape instanceof StructureShape) {
                    $objectClass = $this->generate($mapValueShape, false, $strictEnum);
                    $memberCode = strtr('array_map([CLASS::class, "create"], $input["NAME"])', ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
                } else {
                    $memberCode = strtr('$input["NAME"]', ['NAME' => $member->getName()]);
                }
            } else {
                $memberCode = strtr('$input["NAME"]', ['NAME' => $member->getName()]);
            }
            if ($member->isRequired()) {
                $fallback = strtr('$this->throwException(new InvalidArgument(\'Missing required field "NAME".\'))', ['NAME' => $member->getName()]);
                $classBuilder->addUse(InvalidArgument::class);
                $needsThrowMethod = true;
            } else {
                $fallback = 'null';
            }
            $constructorBody .= strtr('$this->PROPERTY = isset($input["NAME"]) ? MEMBER_CODE : FALLBACK;' . "\n", [
                'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                'NAME' => $member->getName(),
                'MEMBER_CODE' => $memberCode,
                'FALLBACK' => $fallback,
            ]);
        }
        $constructor->setBody($constructorBody);

        if ($needsThrowMethod) {
            $throwMethod = $classBuilder->addMethod('throwException');
            $throwMethod->setPrivate();
            $throwMethod->addComment('@return never');
            $throwMethod->addParameter('exception')->setType(\Throwable::class);
            $throwMethod->setBody('throw $exception;');
        }
    }

    /**
     * Add properties and getters.
     */
    private function addProperties(StructureShape $shape, ClassBuilder $classBuilder, bool $forEndpoint, bool $strictEnum): void
    {
        $forEndpointProps = $forEndpoint ? ['address' => false, 'cachePeriodInMinutes' => false] : [];
        foreach ($shape->getMembers() as $member) {
            $nullable = $returnType = null;
            $memberShape = $member->getShape();
            $property = $classBuilder->addProperty($propertyName = GeneratorHelper::normalizeName($member->getName()))->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentationMember()) {
                $property->setComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($memberShape, $strictEnum);
            if ($forEndpoint && isset($forEndpointProps[$propertyName])) {
                $forEndpointProps[$propertyName] = true;
            }
            foreach ($memberClassNames as $memberClassName) {
                $classBuilder->addUse($memberClassName->getFqdn());
            }

            if (!empty($memberShape->getEnum())) {
                $enumClassName = $this->enumGenerator->generate($memberShape);
                $classBuilder->addUse($enumClassName->getFqdn());
            }
            $getterSetterNullable = null;

            if ($memberShape instanceof StructureShape) {
                $this->generate($memberShape, false, $strictEnum);
            } elseif ($memberShape instanceof MapShape) {
                $getterSetterNullable = false;
                $mapKeyShape = $memberShape->getKey()->getShape();
                if ('string' !== $mapKeyShape->getType()) {
                    throw new \RuntimeException('Complex maps are not supported');
                }
                if (!empty($mapKeyShape->getEnum())) {
                    $this->enumGenerator->generate($mapKeyShape);
                }

                if (($valueShape = $memberShape->getValue()->getShape()) instanceof StructureShape) {
                    $this->generate($valueShape, false, $strictEnum);
                }
                if (!empty($valueShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($valueShape);
                    $classBuilder->addUse($enumClassName->getFqdn());
                }
            } elseif ($memberShape instanceof ListShape) {
                $getterSetterNullable = false;
                $memberShape->getMember()->getShape();

                if (($memberShape = $memberShape->getMember()->getShape()) instanceof StructureShape) {
                    $this->generate($memberShape, false, $strictEnum);
                }
                if (!empty($memberShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($memberShape);
                    $classBuilder->addUse($enumClassName->getFqdn());
                }
            } elseif ($memberShape instanceof DocumentShape) {
                $nullable = false;
            } elseif ($member->isStreaming()) {
                $returnType = ResultStream::class;
                $parameterType = ResultStream::class;
                $classBuilder->addUse(ResultStream::class);
                $nullable = false;
            }

            $method = $classBuilder->addMethod('get' . ucfirst(GeneratorHelper::normalizeName($member->getName())))
                ->setReturnType($returnType);

            $deprecation = '';
            if ($member->isDeprecated()) {
                $method->addComment('@deprecated');
                $deprecation = strtr('@trigger_error(\sprintf(\'The property "NAME" of "%s" is deprecated by AWS.\', __CLASS__), E_USER_DEPRECATED);', ['NAME' => $member->getName()]);
            }

            $nullable = $nullable ?? !$member->isRequired();
            $getterSetterNullable = $getterSetterNullable ?? $nullable;

            if ($getterSetterNullable || !$nullable) {
                $method->setBody($deprecation . strtr('
                        return $this->PROPERTY;
                    ', [
                    'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                ]));
            } else {
                $method->setBody($deprecation . strtr('
                        return $this->PROPERTY ?? [];
                    ', [
                    'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                ]));
            }

            if ($parameterType && $parameterType !== $returnType && (empty($memberClassNames) || $memberClassNames[0]->getName() !== $parameterType)) {
                $method->addComment('@return ' . $parameterType . ($getterSetterNullable ? '|null' : ''));
            }
            $method->setReturnNullable($getterSetterNullable);
            if ($parameterType) {
                $property->addComment('@var ' . $parameterType . ($nullable ? '|null' : ''));
            }
        }

        foreach ($forEndpointProps as $key => $ok) {
            if ($ok) {
                continue;
            }

            throw new \LogicException(\sprintf('Missing Endpoint property "%s" in "%s" object', $key, $shape->getName()));
        }
    }
}
