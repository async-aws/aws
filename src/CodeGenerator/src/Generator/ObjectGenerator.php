<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\ObjectShape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Definition\UnionShape;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\CodeGenerator\Generator\RequestSerializer\SerializerProvider;
use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Exception\LogicException;
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
     * @var ShapeUsageHelper
     */
    private $shapeUsageHelper;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry, ShapeUsageHelper $shapeUsageHelper, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($this->classRegistry, $this->namespaceRegistry, $shapeUsageHelper);
        $this->serializer = new SerializerProvider($this->namespaceRegistry, $requirementsRegistry);
        $this->shapeUsageHelper = $shapeUsageHelper;
    }

    public function generate(ObjectShape $shape, bool $forEndpoint = false): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        if ($shape instanceof StructureShape) {
            return $this->generateStructure($shape, $forEndpoint);
        }

        if ($shape instanceof UnionShape) {
            if ($forEndpoint) {
                throw new InvalidArgument('Union shapes are not supported for endpoint discovery.');
            }

            return $this->generateUnion($shape);
        }

        throw new InvalidArgument('Unsupported object shape: ' . \get_class($shape));
    }

    private function generateStructure(StructureShape $shape, bool $forEndpoint = false): ClassName
    {
        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getObject($shape);

        $classBuilder = $this->classRegistry->register($className->getFqdn());
        $classBuilder->setFinal();
        if (null !== $documentation = $shape->getDocumentationMain()) {
            $classBuilder->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        // Named constructor
        $this->generateConstructor($shape, $classBuilder);
        $this->generateNamedConstructor($shape, $classBuilder);
        $this->addProperties($shape, $classBuilder, $forEndpoint);

        if ($forEndpoint) {
            $classBuilder->addUse(EndpointInterface::class);
            $classBuilder->addImplement(EndpointInterface::class);
        }

        $serializer = $this->serializer->get($shape->getService());
        if ($this->shapeUsageHelper->isShapeUsedInput($shape)) {
            $serializerBuilderResult = $serializer->generateRequestBuilder($shape, false);
            foreach ($serializerBuilderResult->getUsedClasses() as $classNameFqdn) {
                $classBuilder->addUse($classNameFqdn);
            }

            $method = $classBuilder->addMethod('requestBody')->setReturnType($serializer->getRequestBuilderReturnType())->setBody($serializerBuilderResult->getBody())->setPublic()->setComment('@internal');
            foreach ($serializer->getRequestBuilderExtraArguments() as $arg => $type) {
                $method->addParameter($arg)->setType($type);
            }
        }

        return $className;
    }

    private function generateUnion(UnionShape $shape): ClassName
    {
        $this->generated[$shape->getName()] = $abstractClassName = $this->namespaceRegistry->getObject($shape);

        // first generates the Abstract class
        $classBuilder = $classBuilderForAbstract = $this->classRegistry->register($abstractClassName->getFqdn());
        $classBuilder->setAbstract();
        if (null !== $documentation = $shape->getDocumentationMain()) {
            $classBuilder->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        $this->generateNamedConstructorForUnion($shape, $classBuilder);

        if ($this->shapeUsageHelper->isShapeUsedInput($shape)) {
            $serializer = $this->serializer->get($shape->getService());

            $method = $classBuilder->addMethod('requestBody')
                ->setReturnType($serializer->getRequestBuilderReturnType())
                ->setAbstract()
                ->setPublic()
                ->setComment('@internal');
            foreach ($serializer->getRequestBuilderExtraArguments() as $arg => $type) {
                $method->addParameter($arg)->setType($type);
            }
        }

        $serializer = $this->serializer->get($shape->getService());
        // generate one class per member
        $inheritors = [];
        foreach ($shape->getChildren() as $child) {
            $this->generated[$child->getName()] = $childClassName = $this->namespaceRegistry->getObject($child);
            $classBuilder = $this->classRegistry->register($childClassName->getFqdn());
            $classBuilder->setFinal()->setExtends($abstractClassName->getFqdn());
            $inheritors[] = $childClassName->getName();

            $this->generateConstructor($child, $classBuilder);
            $this->addProperties($child, $classBuilder, false);

            if ($this->shapeUsageHelper->isShapeUsedInput($shape)) {
                $serializerBuilderResult = $serializer->generateRequestBuilder($child, false);
                foreach ($serializerBuilderResult->getUsedClasses() as $classNameFqdn) {
                    $classBuilder->addUse($classNameFqdn);
                }

                $method = $classBuilder->addMethod('requestBody')->setReturnType($serializer->getRequestBuilderReturnType())->setBody($serializerBuilderResult->getBody())->setPublic()->setComment('@internal');
                foreach ($serializer->getRequestBuilderExtraArguments() as $arg => $type) {
                    $method->addParameter($arg)->setType($type);
                }
            }
        }

        if ($this->shapeUsageHelper->isShapeUsedOutput($shape)) {
            // generate fallback for unknown member
            $child = $shape->getChildForUnknown();
            $this->generated[$child->getName()] = $childClassName = $this->namespaceRegistry->getObject($child);
            $classBuilder = $this->classRegistry->register($childClassName->getFqdn());
            $classBuilder->setFinal()->setExtends($abstractClassName->getFqdn());
            $inheritors[] = $childClassName->getName();

            $classBuilder->addMethod('__construct')
                ->setComment('@internal')
                ->addComment('@param array<string, mixed> $input')
                ->setBody('$this->unknown = $input;')
                ->addParameter('input')->setType('array')
            ;

            $classBuilder->addProperty('unknown')
                ->setPrivate()
                ->addComment('@var array<string, mixed>');

            $classBuilder->addMethod('getUnknown')
                ->setReturnType('array')
                ->addComment('@return array<string, mixed>')
                ->setBody('return $this->unknown;');

            if ($this->shapeUsageHelper->isShapeUsedInput($shape)) {
                $method = $classBuilder->addMethod('requestBody')
                    ->setPublic()
                    ->setReturnType($serializer->getRequestBuilderReturnType())
                    ->setComment('@internal')
                    ->setBody('throw new LogicException(\'request can not be generated for unknown object\');');
                $classBuilder->addUse(LogicException::class);
                foreach ($serializer->getRequestBuilderExtraArguments() as $arg => $type) {
                    $method->addParameter($arg)->setType($type);
                }
            }
        }

        $classBuilderForAbstract->addComment('@psalm-inheritors ' . implode('|', $inheritors));

        return $abstractClassName;
    }

    private function generateNamedConstructor(StructureShape $shape, ClassBuilder $classBuilder): void
    {
        if (empty($shape->getMembers())) {
            $createMethod = $classBuilder->addMethod('create')
                ->setStatic(true)
                ->setReturnType('self')
                ->setBody('return $input instanceof self ? $input : new self();');
            $createMethod->addParameter('input');
            [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], true, false, true);
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
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], true, false, true);
        $createMethod->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }
    }

    private function generateNamedConstructorForUnion(UnionShape $shape, ClassBuilder $classBuilder): void
    {
        $body = ['if ($input instanceof self) {
            return $input;
        }'];
        /** @var StructureShape $children */
        foreach ($shape->getChildren() as $name => $children) {
            $memberClassName = $this->namespaceRegistry->getObject($children);
            $classBuilder->addUse($memberClassName->getFqdn());
            $body[] = strtr('if (isset($input[NAME])) {
                return new CLASS([NAME => $input[NAME]]);
            }', [
                'NAME' => var_export($name, true),
                'CLASS' => $memberClassName->getName(),
            ]);
        }
        $classBuilder->addUse(InvalidArgument::class);
        $body[] = 'throw new InvalidArgument(\'Invalid union input\');';

        $createMethod = $classBuilder->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody(implode("\n", $body));
        $createMethod->addParameter('input');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], true, false, true);
        $createMethod->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }
    }

    private function generateConstructor(StructureShape $shape, ClassBuilder $classBuilder): void
    {
        if (empty($shape->getMembers())) {
            return;
        }
        $constructor = $classBuilder->addMethod('__construct');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], false, false, true);
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
            if ($memberShape instanceof ObjectShape) {
                $objectClass = $this->generate($memberShape);
                $memberCode = strtr('CLASS::create($input["NAME"])', ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // Check if this is a list of objects
                if ($listMemberShape instanceof ObjectShape) {
                    $objectClass = $this->generate($listMemberShape);
                    $memberCode = strtr('array_map([CLASS::class, "create"], $input["NAME"])', ['NAME' => $member->getName(), 'CLASS' => $objectClass->getName()]);
                } else {
                    $memberCode = strtr('$input["NAME"]', ['NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                if ($mapValueShape instanceof ObjectShape) {
                    $objectClass = $this->generate($mapValueShape);
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
    private function addProperties(StructureShape $shape, ClassBuilder $classBuilder, bool $forEndpoint): void
    {
        $forEndpointProps = $forEndpoint ? ['address' => false, 'cachePeriodInMinutes' => false] : [];
        foreach ($shape->getMembers() as $member) {
            $nullable = $returnType = null;
            $memberShape = $member->getShape();
            $property = $classBuilder->addProperty($propertyName = GeneratorHelper::normalizeName($member->getName()))->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentationMember()) {
                $property->setComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($memberShape);
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
            $typeAlreadyNullable = false;

            if ($memberShape instanceof ObjectShape) {
                $this->generate($memberShape);
            } elseif ($memberShape instanceof MapShape) {
                $getterSetterNullable = false;
                $mapKeyShape = $memberShape->getKey()->getShape();
                if ('string' !== $mapKeyShape->getType()) {
                    throw new \RuntimeException('Complex maps are not supported');
                }
                if (!empty($mapKeyShape->getEnum())) {
                    $this->enumGenerator->generate($mapKeyShape);
                }

                if (($valueShape = $memberShape->getValue()->getShape()) instanceof ObjectShape) {
                    $this->generate($valueShape);
                }
                if (!empty($valueShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($valueShape);
                    $classBuilder->addUse($enumClassName->getFqdn());
                }
            } elseif ($memberShape instanceof ListShape) {
                $getterSetterNullable = false;
                $memberShape->getMember()->getShape();

                if (($memberShape = $memberShape->getMember()->getShape()) instanceof ObjectShape) {
                    $this->generate($memberShape);
                }
                if (!empty($memberShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($memberShape);
                    $classBuilder->addUse($enumClassName->getFqdn());
                }
            } elseif ($memberShape instanceof DocumentShape) {
                $nullable = true;
                $typeAlreadyNullable = true;
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
                $method->addComment('@return ' . $parameterType . ($getterSetterNullable && !$typeAlreadyNullable ? '|null' : ''));
            }
            $method->setReturnNullable($getterSetterNullable);
            if ($parameterType) {
                $property->addComment('@var ' . $parameterType . ($nullable && !$typeAlreadyNullable ? '|null' : ''));
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
