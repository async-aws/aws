<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Member;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\RequestSerializer\SerializerProvider;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\ResultStream;
use AsyncAws\Core\Stream\StreamFactory;
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
     * @var ObjectGenerator
     */
    private $objectGenerator;

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

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter, ObjectGenerator $objectGenerator, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
        $this->objectGenerator = $objectGenerator;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($this->namespaceRegistry, $fileWriter);
        $this->serializer = new SerializerProvider($this->namespaceRegistry);
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

        $namespace = new PhpNamespace($className->getNamespace());
        $class = $namespace->addClass($className->getName());
        $class->setFinal();
        if (null !== $documentation = $shape->getDocumentation()) {
            $class->addComment(GeneratorHelper::parseDocumentation($documentation, false));
        }

        $constructorBody = '';

        foreach ($shape->getMembers() as $member) {
            if ('region' === $member->getName()) {
                throw new \RuntimeException('Member conflict with "@region" parameter.');
            }
            $memberShape = $member->getShape();
            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($memberShape);
            foreach ($memberClassNames as $memberClassName) {
                $namespace->addUse($memberClassName->getFqdn());
            }
            $getterSetterNullable = true;

            if ($memberShape instanceof StructureShape) {
                $memberClassName = $this->objectGenerator->generate($memberShape);
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();
                if (!empty($listMemberShape->getEnum())) {
                    $this->enumGenerator->generate($listMemberShape);
                }
                $getterSetterNullable = false;

                if ($listMemberShape instanceof StructureShape) {
                    $getterSetterNullable = false;
                    $memberClassName = $this->objectGenerator->generate($listMemberShape);
                    $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? array_map([CLASS::class, "create"], $input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
                } elseif ($listMemberShape instanceof ListShape) {
                    $getterSetterNullable = false;
                    $listMemberShapelevel2 = $listMemberShape->getMember()->getShape();
                    if (!empty($listMemberShapelevel2->getEnum())) {
                        $this->enumGenerator->generate($listMemberShapelevel2);
                    }

                    if ($listMemberShapelevel2 instanceof StructureShape) {
                        $memberClassName = $this->objectGenerator->generate($listMemberShapelevel2);
                        $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? array_map(static function(array $array) {
                            return array_map([CLASS::class, "create"], $array);
                        }, $input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
                    } elseif ($listMemberShapelevel2 instanceof ListShape || $listMemberShapelevel2 instanceof MapShape) {
                        throw new \RuntimeException('Recursive ListShape are not yet implemented');
                    } else {
                        $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
                    }
                } elseif ($listMemberShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive ListShape are not yet implemented');
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
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
                            $this->NAME = [];
                            foreach ($input["NAME"] as $key => $item) {
                                $this->NAME[$key] = CLASS::create($item);
                            }
                        }
                    ', [
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
                            $this->NAME = [];
                            foreach ($input["NAME"] ?? [] as $key => $item) {
                                $this->NAME[$key] = array_map([CLASS::class, "create"], $item);
                            }
                        }
                    ', [
                        'NAME' => $member->getName(),
                        'CLASS' => $memberClassName->getName(),
                    ]);
                    $namespace->addUse($memberClassName->getFqdn());
                } elseif ($mapValueShape instanceof MapShape) {
                    throw new \RuntimeException('Recursive MapShape are not yet implemented');
                } else {
                    // It is a scalar, like a string
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($member->isStreaming()) {
                $parameterType = 'string|resource|callable|iterable';
                $returnType = null;
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            } elseif ('timestamp' === $memberShape->getType()) {
                $constructorBody .= strtr('$this->NAME = !isset($input["NAME"]) ? null : ($input["NAME"] instanceof \DateTimeImmutable ? $input["NAME"] : new \DateTimeImmutable($input["NAME"]));' . "\n", ['NAME' => $member->getName()]);
            } else {
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            }

            $property = $class->addProperty($member->getName())->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if (!empty($memberShape->getEnum())) {
                $this->enumGenerator->generate($memberShape);
            }

            if ($member->isRequired()) {
                $property->addComment('@required');
            }

            // the "\n" helps php-cs-fixer to with potential wildcard in parameterType
            $property->addComment("\n@var null|$parameterType");

            $getter = $class->addMethod('get' . \ucfirst($member->getName()))
                ->setReturnType($returnType)
                ->setReturnNullable($getterSetterNullable);
            $setter = $class->addMethod('set' . \ucfirst($member->getName()))
                ->setReturnType('self');

            $deprecation = '';
            if ($member->isDeprecated()) {
                $getter->addComment('@deprecated');
                $setter->addComment('@deprecated');
                $deprecation = strtr('@trigger_error(\sprintf(\'The property "NAME" of "%s" is deprecated by AWS.\', __CLASS__), E_USER_DEPRECATED);', ['NAME' => $member->getName()]);
            }
            if ($getterSetterNullable) {
                $getter->setBody($deprecation . strtr('return $this->NAME;', ['NAME' => $member->getName()]));
            } else {
                $getter->setBody($deprecation . strtr('return $this->NAME ?? [];', ['NAME' => $member->getName()]));
            }
            $setter->setBody($deprecation . strtr('
                    $this->NAME = $value;
                    return $this;
                ', [
                'NAME' => $member->getName(),
            ]));
            $setter
                ->addParameter('value')->setType($returnType)->setNullable($getterSetterNullable)
            ;

            if ($returnType !== $parameterType) {
                $setter->addComment('@param ' . $parameterType . ($getterSetterNullable ? '|null' : '') . ' $value');
                $getter->addComment('@return ' . $parameterType . ($getterSetterNullable ? '|null' : ''));
            }
        }

        // Add named constructor
        $class->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody(strtr('
                return $input instanceof self ? $input : new self(ARGS);
            ', ['ARGS' => '$input']))
            ->addParameter('input');

        $constructorBody .= 'parent::__construct($input);';
        $constructor = $class->addMethod('__construct');
        [$doc, $memberClassNames] = $this->typeGenerator->generateDocblock($shape, $className, false, true, false, ['  @region?: string,']);
        $constructor->addComment($doc);
        foreach ($memberClassNames as $memberClassName) {
            $namespace->addUse($memberClassName->getFqdn());
        }
        $constructor->addParameter('input')->setType('array')->setDefaultValue([]);
        $constructor->setBody($constructorBody);

        $namespace->addUse(Request::class);
        $namespace->addUse(StreamFactory::class);
        $this->inputClassRequestGetters($shape, $class, $operation);

        $namespace->addUse(InvalidArgument::class);
        $this->addUse($shape, $namespace);

        $class->addExtend(Input::class);
        $namespace->addUse(Input::class);

        $this->fileWriter->write($namespace);

        return $className;
    }

    private function inputClassRequestGetters(StructureShape $inputShape, ClassType $class, Operation $operation): void
    {
        $serializer = $this->serializer->get($operation->getService());

        if ((null !== $payloadProperty = $inputShape->getPayload()) && $inputShape->getMember($payloadProperty)->isStreaming()) {
            $body['header'] = '$headers = [];' . "\n";
        } else {
            $body['header'] = '$headers = ' . $serializer->getHeaders($operation) . ';' . "\n";
        }

        $body['querystring'] = '$query = [];' . "\n";

        foreach (['header' => '$headers', 'querystring' => '$query', 'uri' => '$uri'] as $requestPart => $varName) {
            foreach ($inputShape->getMembers() as $member) {
                // If location is not specified, it will go in the request body.
                if ($requestPart !== $member->getLocation()) {
                    continue;
                }

                $memberShape = $member->getShape();
                if ($member->isRequired()) {
                    $bodyCode = 'if (null === $v = $this->PROPERTY) {
                        throw new InvalidArgument(sprintf(\'Missing parameter "PROPERTY" for "%s". The value cannot be null.\', __CLASS__));
                    }
                    VALIDATE_ENUM
                    VAR_NAME["LOCATION"] = VALUE;';
                    $inputElement = '$v';
                } else {
                    $bodyCode = 'if (null !== $this->PROPERTY) {
                        VALIDATE_ENUM
                        VAR_NAME["LOCATION"] = VALUE;
                    }';
                    $inputElement = '$this->' . $member->getName();
                }
                $validateEnum = '';
                if (!empty($memberShape->getEnum())) {
                    $enumClassName = $this->namespaceRegistry->getEnum($memberShape);
                    $validateEnum = strtr('if (!ENUM_CLASS::exists(VALUE)) {
                        throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" for "%s". The value "%s" is not a valid "ENUM_CLASS".\', __CLASS__, $this->PROPERTY));
                    }', [
                        'VALUE' => $this->stringify($inputElement, $member, $requestPart),
                        'ENUM_CLASS' => $enumClassName->getName(),
                        'PROPERTY' => $member->getName(),
                    ]);
                }

                $bodyCode = strtr($bodyCode, [
                    'PROPERTY' => $member->getName(),
                    'VAR_NAME' => $varName,
                    'LOCATION' => $member->getLocationName() ?? $member->getName(),
                    'VALIDATE_ENUM' => $validateEnum,
                    'VALUE' => $this->stringify($inputElement, $member, $requestPart),
                ]);
                if (!isset($body[$requestPart])) {
                    $body[$requestPart] = $varName . ' = [];' . "\n";
                }
                $body[$requestPart] .= implode("\n", \array_filter(array_map('trim', explode("\n", $bodyCode))));
            }
        }

        // "headers" are not "header"
        foreach ($inputShape->getMembers() as $member) {
            if ('headers' !== $member->getLocation()) {
                continue;
            }

            $memberShape = $member->getShape();
            $inputElement = '$this->' . $member->getName();
            if (!$memberShape instanceof MapShape) {
                throw new \InvalidArgumentException(sprintf('Headers only supports MapShape. "%s" given', $memberShape->getType()));
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
                'PROPERTY' => $member->getName(),
                'LOCATION' => $member->getLocationName() ?? $member->getName(),
                'VALUE' => $inputElement,
            ]);
            $body['header'] .= implode("\n", \array_filter(array_map('trim', explode("\n", $bodyCode))));
        }

        if ($operation->hasBody()) {
            [$body['body'], $hasRequestBody, $overrideArgs] = $serializer->generateRequestBody($operation, $inputShape) + [null, null, []];
            if ($hasRequestBody) {
                [$returnType, $requestBody, $args] = $serializer->generateRequestBuilder($inputShape) + [null, null, []];
                $method = $class->addMethod('requestBody')->setReturnType($returnType)->setBody($requestBody)->setPrivate();
                foreach ($overrideArgs + $args as $arg => $type) {
                    $method->addParameter($arg)->setType($type);
                }
            }
        } else {
            $body['body'] = '$body = "";';
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
        $body['uri'] = $body['uri'] ?? '';
        $uriStringCode = '"' . $operation->getHttpRequestUri() . '"';
        $uriStringCode = \preg_replace('/\{([^\}\+]+)\+\}/', '".str_replace(\'%2F\', \'/\', rawurlencode($uri[\'$1\']))."', $uriStringCode);
        $uriStringCode = \preg_replace('/\{([^\}]+)\}/', '".rawurlencode($uri[\'$1\'])."', $uriStringCode);
        $uriStringCode = \preg_replace('/(^""\.|\.""$|\.""\.)/', '', $uriStringCode);
        $body['uri'] .= '$uriString = ' . $uriStringCode . ';';

        $method = \var_export($operation->getHttpMethod(), true);

        $class->addMethod('request')->setComment('@internal')->setReturnType(Request::class)->setBody(<<<PHP

// Prepare headers
{$body['header']}

// Prepare query
{$body['querystring']}

// Prepare URI
{$body['uri']}

// Prepare Body
{$body['body']}

// Return the Request
return new Request($method, \$uriString, \$query, \$headers, StreamFactory::create(\$body));
PHP
);
    }

    /**
     * Convert variable to a string.
     */
    private function stringify(string $variable, Member $member, string $part): string
    {
        if ('header' !== $part && 'querystring' !== $part && 'uri' !== $part) {
            throw new \InvalidArgumentException(sprintf('Argument 3 of "%s::%s" must be either "header" or "querystring" or "uri". Value "%s" provided', __CLASS__, __FUNCTION__, $part));
        }

        $shape = $member->getShape();
        switch ($shape->getType()) {
            case 'timestamp':
                $format = strtoupper($shape->get('timestampFormat') ?? ('header' === $part ? 'rfc822' : 'iso8601'));
                if (!\defined('\DateTimeInterface::' . $format)) {
                    throw new \InvalidArgumentException('Constant "\DateTimeInterface::' . $format . '" does not exists.');
                }

                return $variable . '->format(\DateTimeInterface::' . $format . ')';
            case 'boolean':
                return $variable . ' ? "true" : "false"';
            case 'string':
            case 'long':
                return $variable;
            case 'integer':
            return '(string) ' . $variable;
        }

        throw new \InvalidArgumentException(sprintf('Type "%s" is not yet implemented', $shape->getType()));
    }

    private function addUse(StructureShape $shape, PhpNamespace $namespace, array $addedFqdn = [])
    {
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            if (!empty($memberShape->getEnum())) {
                $namespace->addUse($this->namespaceRegistry->getEnum($memberShape)->getFqdn());
            }

            if ($memberShape instanceof StructureShape) {
                $fqdn = $this->namespaceRegistry->getObject($memberShape)->getFqdn();
                if (!\in_array($fqdn, $addedFqdn)) {
                    $addedFqdn[] = $fqdn;
                    $this->addUse($memberShape, $namespace, $addedFqdn);
                    $namespace->addUse($fqdn);
                }
            } elseif ($memberShape instanceof MapShape) {
                if (($valueShape = $memberShape->getValue()->getShape()) instanceof StructureShape) {
                    $fqdn = $this->namespaceRegistry->getObject($valueShape)->getFqdn();
                    if (!\in_array($fqdn, $addedFqdn)) {
                        $addedFqdn[] = $fqdn;
                        $this->addUse($valueShape, $namespace, $addedFqdn);
                        $namespace->addUse($fqdn);
                    }
                }
                if (!empty($valueShape->getEnum())) {
                    $namespace->addUse($this->namespaceRegistry->getEnum($valueShape)->getFqdn());
                }
            } elseif ($memberShape instanceof ListShape) {
                if (($memberShape = $memberShape->getMember()->getShape()) instanceof StructureShape) {
                    $fqdn = $this->namespaceRegistry->getObject($memberShape)->getFqdn();
                    if (!\in_array($fqdn, $addedFqdn)) {
                        $addedFqdn[] = $fqdn;
                        $this->addUse($memberShape, $namespace, $addedFqdn);
                        $namespace->addUse($fqdn);
                    }
                }
                if (!empty($memberShape->getEnum())) {
                    $namespace->addUse($this->namespaceRegistry->getEnum($memberShape)->getFqdn());
                }
            } elseif ($member->isStreaming()) {
                $namespace->addUse(ResultStream::class);
            }
        }
    }
}
