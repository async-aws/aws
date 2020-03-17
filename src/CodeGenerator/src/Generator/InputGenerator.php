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
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Core\StreamableBodyInterface;
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
    use ValidableTrait;

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
        $this->serializer = new SerializerProvider();
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

        $constructorBody = '';

        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            [$returnType, $parameterType, $memberClassName] = $this->typeGenerator->getPhpType($memberShape);
            $nullable = true;
            if ($memberShape instanceof StructureShape) {
                $memberClassName = $this->objectGenerator->generate($memberShape);
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $memberClassName->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();
                $nullable = false;

                if ($listMemberShape instanceof StructureShape) {
                    $memberClassName = $this->objectGenerator->generate($listMemberShape);
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
                    $memberClassName = $this->objectGenerator->generate($mapValueShape);

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
                $this->enumGenerator->generate($memberShape);
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
            if (null !== $documentationUrl = $operation->getDocumentationUrl()) {
                $constructor->addComment('@see ' . $documentationUrl);
            }

            $constructor->addComment($this->typeGenerator->generateDocblock($shape, $className, false, true));
            $constructor->addParameter('input')->setType('array')->setDefaultValue([]);
            $constructor->setBody($constructorBody);
        }
        $namespace->addUse(Request::class);
        $namespace->addUse(StreamFactory::class);
        $this->inputClassRequestGetters($shape, $class, $operation);

        $this->generateValidate($shape, $class, $namespace);

        $this->addUse($shape, $namespace);

        $this->fileWriter->write($namespace);

        return $className;
    }

    private function inputClassRequestGetters(StructureShape $inputShape, ClassType $class, Operation $operation): void
    {
        $serializer = $this->serializer->get($operation->getService());

        if ((null !== $payloadProperty = $inputShape->getPayload()) && $inputShape->getMember($payloadProperty)->isStreaming()) {
            $body['header'] = '$headers = [];' . "\n";
        } else {
            $body['header'] = '$headers = [\'content-type\' => \'' . $serializer->getContentType() . '\'];' . "\n";
        }

        $body['querystring'] = '$query = [];' . "\n";

        foreach (['header' => '$headers', 'querystring' => '$query'] as $requestPart => $varName) {
            foreach ($inputShape->getMembers() as $member) {
                // If location is not specified, it will go in the request body.
                if ($requestPart === ($member->getLocation() ?? 'payload')) {
                    $body[$requestPart] .= 'if ($this->' . $member->getName() . ' !== null) ' . $varName . '["' . ($member->getLocationName() ?? $member->getName()) . '"] = ' . $this->stringify('$this->' . $member->getName(), $member, $requestPart) . ';' . "\n";
                }
            }
        }

        if ($operation->hasBody()) {
            [$body['body'], $hasRequestBody, $overrideArgs] = $serializer->generateRequestBody($operation, $inputShape) + [null, null, []];
            if ($hasRequestBody) {
                [$returnType, $requestBody, $args] = $serializer->generateRequestBuilder($inputShape) + [null, null, []];
                $method = $class->addMethod('requestBody')->setReturnType($returnType)->setBody($requestBody)->setPrivate()->setComment('@internal');
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
        foreach ($inputShape->getMembers() as $member) {
            if ('uri' === $member->getLocation()) {
                if (!isset($requestUri)) {
                    $requestUri = '$uri = [];' . "\n";
                }
                $requestUri .= \strtr('$uri["LOCATION"] = $this->NAME ?? "";', ['NAME' => $member->getName(), 'LOCATION' => $member->getLocationName()]);
            }
        }

        $body['uri'] = $requestUri ?? '';
        $body['uri'] .= '$uriString = "' . str_replace(['{', '+}', '}'], ['{$uri[\'', '}', '\']}'], $operation->getHttpRequestUri()) . '";';

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
        if ('header' !== $part && 'querystring' !== $part) {
            throw new \InvalidArgumentException(sprintf('Argument 3 of "%s::%s" must be either "header" or "querystring". Value "%s" provided', __CLASS__, __FUNCTION__, $part));
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
                return $variable;
            case 'long':
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
                $namespace->addUse(StreamableBodyInterface::class);
            }
        }
    }
}
