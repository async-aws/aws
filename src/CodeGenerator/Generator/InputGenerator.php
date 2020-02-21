<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\ArrayDumper;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\TypeGenerator;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\XmlDumper;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
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
     * @var XmlDumper
     */
    private $xmlDumper;

    /**
     * @var ArrayDumper
     */
    private $arrayDumper;

    /**
     * @var ClassName[]
     */
    private $generated = [];

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter, ?TypeGenerator $typeGenerator = null, ?XmlDumper $xmlDumper = null, ?ArrayDumper $arrayDumper = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->arrayDumper = $arrayDumper ?? new ArrayDumper();
        $this->xmlDumper = $xmlDumper ?? new XmlDumper();
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
        $requiredProperties = [];

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
                $parameterType = 'string|resource|\Closure';
                $returnType = null;
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            } elseif ('\DateTimeInterface' !== $parameterType) {
                $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? null;' . "\n", ['NAME' => $member->getName()]);
            } else {
                $constructorBody .= strtr('$this->NAME = !isset($input["NAME"]) ? null : ($input["NAME"] instanceof \DateTimeInterface ? $input["NAME"] : new \DateTimeImmutable($input["NAME"]));' . "\n", ['NAME' => $member->getName()]);
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

        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            if ($memberShape instanceof StructureShape) {
                $validateBody .= 'if ($this->' . $member->getName() . ') $this->' . $member->getName() . '->validate();' . "\n";
            } elseif (($memberShape instanceof ListShape && $memberShape->getMember()->getShape() instanceof StructureShape) || ($memberShape instanceof MapShape && $memberShape->getValue()->getShape() instanceof StructureShape)) {
                $validateBody .= 'foreach ($this->' . $member->getName() . ' as $item) $item->validate();' . "\n";
            }
        }

        $class->addMethod('validate')->setPublic()->setReturnType('void')->setBody(empty($validateBody) ? '// There are no required properties' : $validateBody);

        $this->fileWriter->write($namespace);

        return $className;
    }

    private function inputClassRequestGetters(StructureShape $inputShape, ClassType $class, Operation $operation): void
    {
        foreach (['header' => '$headers', 'querystring' => '$query'] as $requestPart => $varName) {
            $body[$requestPart] = $varName . ' = [];' . "\n";
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

        if (null !== $payloadProperty = $inputShape->getPayload()) {
            $member = $inputShape->getMember($payloadProperty);
            if ($member->isStreaming()) {
                $bodyType = null;
                $body = 'return $this->' . $payloadProperty . ' ?? "";';
            } else {
                $bodyType = 'string';
                $body = $this->xmlDumper->dumpXml($member, $payloadProperty);
            }
        } else {
            $bodyType = 'array';
            $body = strtr('
                $payload = [\'Action\' => OPERATION_NAME, \'Version\' => API_VERSION];
                CHILDREN_CODE
                return $payload;
            ', [
                'OPERATION_NAME' => \var_export($operation->getName(), true),
                'API_VERSION' => \var_export($operation->getApiVersion(), true),
                'CHILDREN_CODE' => $this->arrayDumper->dumpArray($inputShape),
            ]);
        }
        $class->addMethod('requestBody')->setReturnType($bodyType)->setBody($body);

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
