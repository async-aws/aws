<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\CodeGenerator\Generator\RequestSerializer\SerializerProvider;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\ResultStream;
use AsyncAws\Core\Stream\StreamFactory;

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
     * @var ClassRegistry
     */
    private $classRegistry;

    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    /**
     * @var ObjectGenerator
     */
    private $objectGenerator;

    /**
     * @var EnumGenerator
     */
    private $enumGenerator;

    /**
     * @var HookGenerator
     */
    private $hookGenerator;

    /**
     * @var SerializerProvider
     */
    private $serializer;

    /**
     * @var ClassName[]
     */
    private $generated = [];

    /**
     * @var RequirementsRegistry
     */
    private $requirementsRegistry;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry, ObjectGenerator $objectGenerator, array $managedMethods, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null, ?HookGenerator $hookGenerator = null)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->objectGenerator = $objectGenerator;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($this->classRegistry, $this->namespaceRegistry, $managedMethods);
        $this->hookGenerator = $hookGenerator ?? new HookGenerator();
        $this->serializer = new SerializerProvider($this->namespaceRegistry, $requirementsRegistry);
        $this->requirementsRegistry = $requirementsRegistry;
    }

    /**
     * Generate classes for the input. Ie, the request of the API call.
     */
    public function generate(Operation $operation): ClassName
    {
        $shape = $operation->getInput();
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getInput($shape);

        $classBuilder = $this->classRegistry->register($className->getFqdn());
        $classBuilder->setFinal();
        if (null !== $documentation = $shape->getDocumentationMain()) {
            $classBuilder->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        $constructorBody = '';

        foreach ($shape->getMembers() as $member) {
            if ('region' === $member->getName()) {
                throw new \RuntimeException('Member conflict with "@region" parameter.');
            }
            $memberShape = $member->getShape();
            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($memberShape);
            foreach ($memberClassNames as $memberClassName) {
                $classBuilder->addUse($memberClassName->getFqdn());
            }
            $getterSetterNullable = true;
            $typeAlreadyNullable = false;

            if ($memberShape instanceof StructureShape) {
                $memberClassName = $this->objectGenerator->generate($memberShape);
                $constructorBody .= strtr('$this->PROPERTY = isset($input["NAME"]) ? CLASS::create($input["NAME"]) : null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();
                if (!empty($listMemberShape->getEnum())) {
                    $this->enumGenerator->generate($listMemberShape);
                }
                $getterSetterNullable = false;

                if ($listMemberShape instanceof StructureShape) {
                    $getterSetterNullable = false;
                    $memberClassName = $this->objectGenerator->generate($listMemberShape);
                    $constructorBody .= strtr('$this->PROPERTY = isset($input["NAME"]) ? array_map([CLASS::class, "create"], $input["NAME"]) : null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
                } elseif ($listMemberShape instanceof ListShape) {
                    $getterSetterNullable = false;
                    $listMemberShapelevel2 = $listMemberShape->getMember()->getShape();
                    if (!empty($listMemberShapelevel2->getEnum())) {
                        $this->enumGenerator->generate($listMemberShapelevel2);
                    }

                    if ($listMemberShapelevel2 instanceof StructureShape) {
                        $memberClassName = $this->objectGenerator->generate($listMemberShapelevel2);
                        $constructorBody .= strtr('$this->PROPERTY = isset($input["NAME"]) ? array_map(static function(array $array) {
                            return array_map([CLASS::class, "create"], $array);
                        }, $input["NAME"]) : null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
                    } elseif ($listMemberShapelevel2 instanceof ListShape || $listMemberShapelevel2 instanceof MapShape) {
                        throw new \RuntimeException('Recursive ListShape are not yet implemented');
                    } else {
                        $constructorBody .= strtr('$this->PROPERTY = $input["NAME"] ?? null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]);
                    }
                } elseif ($listMemberShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive ListShape are not yet implemented');
                } else {
                    $constructorBody .= strtr('$this->PROPERTY = $input["NAME"] ?? null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapKeyShape = $memberShape->getKey()->getShape();
                if (!empty($mapKeyShape->getEnum())) {
                    $this->enumGenerator->generate($mapKeyShape);
                }
                $mapValueShape = $memberShape->getValue()->getShape();
                if (!empty($mapValueShape->getEnum())) {
                    $this->enumGenerator->generate($mapValueShape);
                }

                $getterSetterNullable = false;
                // Is this a list of objects?
                if ($mapValueShape instanceof StructureShape) {
                    $memberClassName = $this->objectGenerator->generate($mapValueShape);

                    $constructorBody .= strtr('
                        if (isset($input["NAME"])) {
                            $this->PROPERTY = [];
                            foreach ($input["NAME"] as $key => $item) {
                                $this->PROPERTY[$key] = CLASS::create($item);
                            }
                        }
                    ', [
                        'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                        'NAME' => $member->getName(),
                        'CLASS' => $memberClassName->getName(),
                    ]);
                } elseif ($mapValueShape instanceof ListShape) {
                    $listMember = $mapValueShape->getMember();
                    $listMemberShape = $listMember->getShape();
                    if (!$listMemberShape instanceof StructureShape) {
                        throw new \RuntimeException('Recursive ListShape with non StructureShape member is not implemented.');
                    }
                    $memberClassName = $this->objectGenerator->generate($listMemberShape);
                    $constructorBody .= strtr('
                        if (isset($input["NAME"])) {
                            $this->PROPERTY = [];
                            foreach ($input["NAME"] as $key => $item) {
                                $this->PROPERTY[$key] = array_map([CLASS::class, "create"], $item);
                            }
                        }
                    ', [
                        'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                        'NAME' => $member->getName(),
                        'CLASS' => $memberClassName->getName(),
                    ]);
                    $classBuilder->addUse($memberClassName->getFqdn());
                } elseif ($mapValueShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive MapShape are not yet implemented');
                } else {
                    // It is a scalar, like a string
                    $constructorBody .= strtr('$this->PROPERTY = $input["NAME"] ?? null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof DocumentShape) {
                $typeAlreadyNullable = true; // The type itself is already nullable
            } elseif ($member->isStreaming()) {
                $parameterType = 'string|resource|(callable(int): string)|iterable<string>';
                $returnType = null;
                $constructorBody .= strtr('$this->PROPERTY = $input["NAME"] ?? null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]);
            } elseif ('timestamp' === $memberShape->getType()) {
                $constructorBody .= strtr('$this->PROPERTY = !isset($input["NAME"]) ? null : ($input["NAME"] instanceof \DateTimeImmutable ? $input["NAME"] : new \DateTimeImmutable($input["NAME"]));' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]);
            } else {
                $constructorBody .= strtr('$this->PROPERTY = $input["NAME"] ?? null;' . "\n", ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]);
            }

            $property = $classBuilder->addProperty(GeneratorHelper::normalizeName($member->getName()))->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentationMember()) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if (!empty($memberShape->getEnum())) {
                $this->enumGenerator->generate($memberShape);
            }

            if ($member->isRequired()) {
                $property->addComment('@required');
            }

            // the "\n" helps php-cs-fixer to with potential wildcard in parameterType
            $property->addComment("\n@var $parameterType" . ($typeAlreadyNullable ? '' : '|null'));

            $getter = $classBuilder->addMethod('get' . ucfirst(GeneratorHelper::normalizeName($member->getName())))
                ->setReturnType($returnType)
                ->setReturnNullable($getterSetterNullable);
            $setter = $classBuilder->addMethod('set' . ucfirst(GeneratorHelper::normalizeName($member->getName())))
                ->setReturnType('self');

            $deprecation = '';
            if ($member->isDeprecated()) {
                $getter->addComment('@deprecated');
                $setter->addComment('@deprecated');
                $deprecation = strtr('@trigger_error(\sprintf(\'The property "NAME" of "%s" is deprecated by AWS.\', __CLASS__), E_USER_DEPRECATED);', ['NAME' => $member->getName()]);
            }
            if ($getterSetterNullable) {
                $getter->setBody($deprecation . strtr('return $this->PROPERTY;', ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]));
            } else {
                $getter->setBody($deprecation . strtr('return $this->PROPERTY ?? [];', ['PROPERTY' => GeneratorHelper::normalizeName($member->getName()), 'NAME' => $member->getName()]));
            }
            $setter->setBody($deprecation . strtr('
                    $this->PROPERTY = $value;
                    return $this;
                ', [
                'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                'NAME' => $member->getName(),
            ]));
            $setter
                ->addParameter('value')->setType($returnType)->setNullable($getterSetterNullable)
            ;

            if ($returnType !== $parameterType) {
                $setter->addComment('@param ' . $parameterType . ($getterSetterNullable && !$typeAlreadyNullable ? '|null' : '') . ' $value');
                $getter->addComment('@return ' . $parameterType . ($getterSetterNullable && !$typeAlreadyNullable ? '|null' : ''));
            }
        }

        // Add named constructor
        $createMethod = $classBuilder->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody('return $input instanceof self ? $input : new self($input);');
        $createMethod->addParameter('input');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $className, true, true, false, ['  \'@region\'?: string|null,']);
        $createMethod->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }

        $constructorBody .= 'parent::__construct($input);';
        $constructor = $classBuilder->addMethod('__construct');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $className, false, true, false, ['  \'@region\'?: string|null,']);
        $constructor->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $classBuilder->addUse($memberClassName->getFqdn());
        }
        $constructor->addParameter('input')->setType('array')->setDefaultValue([]);
        $constructor->setBody($constructorBody);

        $classBuilder->addUse(Request::class);
        $classBuilder->addUse(StreamFactory::class);
        $this->inputClassRequestGetters($shape, $classBuilder, $operation);

        $this->addUse($shape, $classBuilder);

        $classBuilder->setExtends(Input::class);
        $classBuilder->addUse(Input::class);

        return $className;
    }

    private function inputClassRequestPartGettersEnumGenerator(Member $member, ClassBuilder $classBuilder, string $requestPart, string $input): string
    {
        $memberShape = $member->getShape();
        $validateEnum = '';
        if (!empty($memberShape->getEnum())) {
            $enumClassName = $this->namespaceRegistry->getEnum($memberShape);
            $classBuilder->addUse(InvalidArgument::class);

            $validateEnum = strtr('if (!ENUM_CLASS::exists(VALUE)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(sprintf(\'Invalid parameter "MEMBER_NAME" for "%s". The value "%s" is not a valid "ENUM_CLASS".\', __CLASS__, INPUT));
            }', [
                'VALUE' => $this->stringify($input, $member, $requestPart),
                'ENUM_CLASS' => $enumClassName->getName(),
                'INPUT' => $input,
            ]);
        }

        return $validateEnum;
    }

    private function inputClassRequestPartGettersHookGenerator(StructureMember $member, Operation $operation, string $requestPart, string $input): string
    {
        $applyHook = '';
        foreach ($operation->getService()->getHooks($requestPart . '_parameters') as $hook) {
            if (!\in_array($member->getLocationName() ?? $member->getName(), $hook->getFilters())) {
                continue;
            }

            $applyHook .= $this->hookGenerator->generate($hook, 'VALUE');
        }

        if (!$applyHook) {
            return '';
        }

        return strtr($applyHook, [
            'VALUE' => $this->stringify($input, $member, $requestPart),
        ]);
    }

    private function inputClassRequestPartGetters(StructureMember $member, ClassBuilder $classBuilder, Operation $operation, string $requestPart, string $input, string $output): string
    {
        $memberShape = $member->getShape();
        if ($memberShape instanceof ListShape) {
            if ('header' === $requestPart) {
                $bodyCode = '
                APPLY_HOOK
                $items = [];
                foreach (INPUT as $value) {
                    VALIDATE_ENUM
                    $items[] = VALUE;
                }
                OUTPUT = implode(\',\', $items);
                ';
            } elseif ('querystring' === $requestPart) {
                $bodyCode = '
                APPLY_HOOK
                foreach (INPUT as $value) {
                    VALIDATE_ENUM
                    OUTPUT[] = VALUE;
                }
                ';
                $this->requirementsRegistry->addRequirement('async-aws/core', '^1.28');
            } else {
                throw new \InvalidArgumentException(\sprintf('ListShape in request part "%s" is not yet implemented.', $requestPart));
            }

            return strtr($bodyCode, [
                'INPUT' => $input,
                'OUTPUT' => $output,
                'VALUE' => $this->stringify('$value', $memberShape->getMember(), $requestPart),
                'VALIDATE_ENUM' => $this->inputClassRequestPartGettersEnumGenerator($memberShape->getMember(), $classBuilder, $requestPart, '$value'),
                'APPLY_HOOK' => $this->inputClassRequestPartGettersHookGenerator($member, $operation, $requestPart, $input),
            ]);
        }

        $bodyCode = '
        VALIDATE_ENUM
        APPLY_HOOK
        OUTPUT = VALUE;';

        return strtr($bodyCode, [
            'VALIDATE_ENUM' => $this->inputClassRequestPartGettersEnumGenerator($member, $classBuilder, $requestPart, $input),
            'APPLY_HOOK' => $this->inputClassRequestPartGettersHookGenerator($member, $operation, $requestPart, $input),
            'OUTPUT' => $output,
            'VALUE' => $this->stringify($input, $member, $requestPart),
        ]);
    }

    private function inputClassRequestGetters(StructureShape $inputShape, ClassBuilder $classBuilder, Operation $operation): void
    {
        $serializer = $this->serializer->get($operation->getService());

        if ((null !== $payloadProperty = $inputShape->getPayload()) && $inputShape->getMember($payloadProperty)->isStreaming()) {
            $body['header'] = '$headers = ' . $serializer->getHeaders($operation, false) . ';' . "\n";
        } else {
            $body['header'] = '$headers = ' . $serializer->getHeaders($operation, true) . ';' . "\n";
        }

        $body['querystring'] = '$query = [];' . "\n";

        $usesEndpointDiscovery = $operation->usesEndpointDiscovery();
        $requestParts = ['header' => '$headers', 'querystring' => '$query', 'uri' => '$uri'];
        foreach ($requestParts as $requestPart => $varName) {
            foreach ($inputShape->getMembers() as $member) {
                // If location is not specified, it will go in the request body.
                if ($requestPart !== $member->getLocation()) {
                    continue;
                }
                if ('querystring' === $requestPart && $usesEndpointDiscovery) {
                    if (0 !== strpos($classBuilder->getClassName()->getFqdn(), 'AsyncAws\Core\\')) {
                        $this->requirementsRegistry->addRequirement('async-aws/core', '^1.19');
                    }
                }

                if (!isset($body[$requestPart])) {
                    $body[$requestPart] = $varName . ' = [];' . "\n";
                }

                if ($member->isRequired()) {
                    $classBuilder->addUse(InvalidArgument::class);
                    $bodyCode = 'if (null === $v = $this->PROPERTY) {
                        throw new InvalidArgument(sprintf(\'Missing parameter "MEMBER_NAME" for "%s". The value cannot be null.\', __CLASS__));
                    }
                    GETTER_CODE';
                    $input = '$v';
                    $output = 'VAR_NAME["LOCATION"]';
                } else {
                    $bodyCode = 'if (null !== $this->PROPERTY) {
                        GETTER_CODE
                    }';
                    $input = '$this->PROPERTY';
                    $output = 'VAR_NAME["LOCATION"]';
                }

                $bodyCode = strtr(strtr($bodyCode, [
                    'GETTER_CODE' => $this->inputClassRequestPartGetters($member, $classBuilder, $operation, $requestPart, $input, $output),
                ]), [
                    'MEMBER_NAME' => $member->getName(),
                    'VAR_NAME' => $varName,
                    'PROPERTY' => GeneratorHelper::normalizeName($member->getName()),
                    'LOCATION' => $member->getLocationName() ?? $member->getName(),
                ]);
                $body[$requestPart] .= $bodyCode . "\n";
            }
        }

        // "headers" are not "header"
        foreach ($inputShape->getMembers() as $member) {
            if ('headers' !== $member->getLocation()) {
                continue;
            }

            $memberShape = $member->getShape();
            $inputElement = '$this->' . GeneratorHelper::normalizeName($member->getName());
            if (!$memberShape instanceof MapShape) {
                throw new \InvalidArgumentException(\sprintf('Headers only supports MapShape. "%s" given', $memberShape->getType()));
            }
            $mapValueShape = $memberShape->getValue()->getShape();
            $keyValueShape = $memberShape->getKey()->getShape();
            if (!empty($mapValueShape->getEnum())) {
                throw new \InvalidArgumentException('Headers does not yet support Enum in value');
            }
            if (!empty($keyValueShape->getEnum())) {
                throw new \InvalidArgumentException('Headers does not yet support Enum in value');
            }

            $bodyCode = strtr('if (null !== VALUE) {
                foreach (VALUE as $key => $value) {
                    $headers["LOCATION$key"] = $value;
                }
            }', [
                'LOCATION' => $member->getLocationName() ?? $member->getName(),
                'VALUE' => $inputElement,
            ]);
            $body['header'] .= $bodyCode . "\n";
        }

        foreach (array_keys($requestParts) as $requestPart) {
            if (isset($body[$requestPart])) {
                $body[$requestPart] = implode("\n", array_filter(array_map('trim', explode("\n", $body[$requestPart]))));
            }
        }

        if ($operation->hasBody()) {
            $serializerBodyResult = $serializer->generateRequestBody($operation, $inputShape);
            $body['body'] = $serializerBodyResult->getBody();
            foreach ($serializerBodyResult->getUsedClasses() as $classNameFqdn) {
                $classBuilder->addUse($classNameFqdn);
            }

            if ($serializerBodyResult->hasRequestBody()) {
                $serializerBuilderResult = $serializer->generateRequestBuilder($inputShape, true);
                foreach ($serializerBuilderResult->getUsedClasses() as $classNameFqdn) {
                    $classBuilder->addUse($classNameFqdn);
                }

                if ('' === trim($serializerBuilderResult->getBody())) {
                    $body['body'] = '$body = "";';
                } else {
                    $method = $classBuilder->addMethod('requestBody')->setReturnType($serializerBuilderResult->getReturnType())->setBody($serializerBuilderResult->getBody())->setPrivate();
                    foreach ($serializerBodyResult->getExtraMethodArgs() + $serializerBuilderResult->getExtraMethodArgs() as $arg => $type) {
                        $method->addParameter($arg)->setType($type);
                    }
                }
            }
        } else {
            $body['body'] = '$body = "";';
            if (null !== $inputShape->getPayload()) {
                throw new \LogicException(\sprintf('Unexpected body in operation "%s"', $operation->getName()));
            }

            foreach ($inputShape->getMembers() as $member) {
                if (null === $member->getLocation()) {
                    throw new \LogicException(\sprintf('Unexpected body in operation "%s"', $operation->getName()));
                }
            }
        }

        $body['uri'] = $body['uri'] ?? '';
        $uriStringCode = '"' . $operation->getHttpRequestUri() . '"';
        $uriStringCode = preg_replace('/\{([^\}\+]+)\+\}/', '".str_replace(\'%2F\', \'/\', rawurlencode($uri[\'$1\']))."', $uriStringCode);
        $uriStringCode = preg_replace('/\{([^\}]+)\}/', '".rawurlencode($uri[\'$1\'])."', $uriStringCode);
        $uriStringCode = preg_replace('/(^""\.|\.""$|\.""\.)/', '', $uriStringCode);

        if ($usesEndpointDiscovery && '"/"' !== $uriStringCode) {
            if (0 !== strpos($classBuilder->getClassName()->getFqdn(), 'AsyncAws\Core\\')) {
                $this->requirementsRegistry->addRequirement('async-aws/core', '^1.19');
            }
        }

        $hostPrefixArgument = '';
        if (null !== $hostPrefix = $operation->getHostPrefix()) {
            if (preg_match('/\{([a-zA-Z0-9]+)}/', $hostPrefix)) {
                throw new \InvalidArgumentException('Parameters are not supported in host prefix yet.');
            }

            $this->requirementsRegistry->addRequirement('async-aws/core', '^1.20');
            $hostPrefixArgument = ', ' . var_export($hostPrefix, true);
        }

        $body['uri'] .= '$uriString = ' . $uriStringCode . ';';

        $method = var_export($operation->getHttpMethod(), true);

        $classBuilder->addMethod('request')->setComment('@internal')->setReturnType(Request::class)->setBody(<<<PHP

// Prepare headers
{$body['header']}

// Prepare query
{$body['querystring']}

// Prepare URI
{$body['uri']}

// Prepare Body
{$body['body']}

// Return the Request
return new Request($method, \$uriString, \$query, \$headers, StreamFactory::create(\$body)$hostPrefixArgument);
PHP
        );
    }

    /**
     * Convert variable to a string.
     */
    private function stringify(string $variable, Member $member, string $part): string
    {
        if ('header' !== $part && 'querystring' !== $part && 'uri' !== $part) {
            throw new \InvalidArgumentException(\sprintf('Argument 3 of "%s::%s" must be either "header" or "querystring" or "uri". Value "%s" provided', __CLASS__, __FUNCTION__, $part));
        }

        $shape = $member->getShape();
        switch ($shape->getType()) {
            case 'timestamp':
                $format = strtoupper($shape->get('timestampFormat') ?? ('header' === $part ? 'rfc822' : 'iso8601'));
                if ('ISO8601' === $format) {
                    $format = 'ATOM';
                }

                if (!\defined('\DateTimeInterface::' . $format)) {
                    throw new \InvalidArgumentException('Constant "\DateTimeInterface::' . $format . '" does not exists.');
                }

                if ('RFC822' === $format || 'RFC7231' === $format) {
                    return $variable . '->setTimezone(new \\DateTimeZone("GMT"))->format(\'D, d M Y H:i:s \G\M\T\')';
                }

                return $variable . '->format(\DateTimeInterface::' . $format . ')';
            case 'boolean':
                return $variable . ' ? "true" : "false"';
            case 'string':
                return $variable;
            case 'long':
            case 'integer':
                return '(string) ' . $variable;
        }

        throw new \InvalidArgumentException(\sprintf('Type "%s" is not yet implemented', $shape->getType()));
    }

    /**
     * @param string[] $addedFqdn
     */
    private function addUse(StructureShape $shape, ClassBuilder $classBuilder, array $addedFqdn = []): void
    {
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            if (!empty($memberShape->getEnum())) {
                $classBuilder->addUse($this->namespaceRegistry->getEnum($memberShape)->getFqdn());
            }

            if ($memberShape instanceof StructureShape) {
                $fqdn = $this->namespaceRegistry->getObject($memberShape)->getFqdn();
                if (!\in_array($fqdn, $addedFqdn)) {
                    $addedFqdn[] = $fqdn;
                    $classBuilder->addUse($fqdn);
                }
            } elseif ($memberShape instanceof MapShape) {
                if (($valueShape = $memberShape->getValue()->getShape()) instanceof StructureShape) {
                    $fqdn = $this->namespaceRegistry->getObject($valueShape)->getFqdn();
                    if (!\in_array($fqdn, $addedFqdn)) {
                        $addedFqdn[] = $fqdn;
                        $classBuilder->addUse($fqdn);
                    }
                }
                if (!empty($valueShape->getEnum())) {
                    $classBuilder->addUse($this->namespaceRegistry->getEnum($valueShape)->getFqdn());
                }
            } elseif ($memberShape instanceof ListShape) {
                if (($memberShape = $memberShape->getMember()->getShape()) instanceof StructureShape) {
                    $fqdn = $this->namespaceRegistry->getObject($memberShape)->getFqdn();
                    if (!\in_array($fqdn, $addedFqdn)) {
                        $addedFqdn[] = $fqdn;
                        $classBuilder->addUse($fqdn);
                    }
                }
                if (!empty($memberShape->getEnum())) {
                    $classBuilder->addUse($this->namespaceRegistry->getEnum($memberShape)->getFqdn());
                }
            } elseif ($member->isStreaming()) {
                $classBuilder->addUse(ResultStream::class);
            }
        }
    }
}
