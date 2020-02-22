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
use AsyncAws\CodeGenerator\Generator\ResponseParser\ParserProvider;
use AsyncAws\Core\Exception\LogicException;
use AsyncAws\Core\Result;
use AsyncAws\Core\StreamableBody;
use AsyncAws\Core\StreamableBodyInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ResultGenerator
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
     * @var ParserProvider
     */
    private $parserProvider;

    /**
     * @var Operation
     */
    private $operation;

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter, ?TypeGenerator $typeGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->parserProvider = new ParserProvider($this->namespaceRegistry);
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generate(Operation $operation): ClassName
    {
        if (null === $output = $operation->getOutput()) {
            throw new LogicException(sprintf('The operation "%s" does not have any output to generate', $operation->getName()));
        }
        $this->operation = $operation;

        return $this->generateResultClass($output, true);
    }

    private function generateResultClass(StructureShape $shape, bool $root = false): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getResult($shape);

        $namespace = new PhpNamespace($className->getNamespace());
        $class = $namespace->addClass($className->getName());

        if ($root) {
            $namespace->addUse(Result::class);
            $class->addExtend(Result::class);

            $namespace->addUse(ResponseInterface::class);
            $namespace->addUse(HttpClientInterface::class);
            $this->populateResult($shape, $namespace, $class);
        } else {
            // Named constructor
            $this->namedConstructor($shape, $class);
        }

        $this->addProperties($root, $shape, $class, $namespace);

        $this->fileWriter->write($namespace);

        return $className;
    }

    private function namedConstructor(StructureShape $shape, ClassType $class): void
    {
        $class->addMethod('create')
            ->setStatic(true)
            ->setReturnType('self')
            ->setBody('return $input instanceof self ? $input : new self($input);')
            ->addParameter('input');

        // We need a constructor
        $constructor = $class->addMethod('__construct');
        $constructor->addComment($this->typeGenerator->generateDocblock($shape, $this->generated[$shape->getName()], false, false, true));
        $constructor->addParameter('input')->setType('array');

        $constructorBody = '';
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            if ($memberShape instanceof StructureShape) {
                $resultClass = $this->generateResultClass($memberShape);
                $constructorBody .= strtr('$this->NAME = isset($input["NAME"]) ? CLASS::create($input["NAME"]) : null;' . "\n", ['NAME' => $member->getName(), 'CLASS' => $resultClass->getName()]);
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // Check if this is a list of objects
                if ($listMemberShape instanceof StructureShape) {
                    $resultClass = $this->generateResultClass($listMemberShape);
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $member->getName(), 'CLASS' => $resultClass->getName()]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                if ($mapValueShape instanceof StructureShape) {
                    $resultClass = $this->generateResultClass($mapValueShape);
                    $constructorBody .= strtr('$this->NAME = array_map(function($item) { return CLASS::create($item); }, $input["NAME"] ?? []);' . "\n", ['NAME' => $member->getName(), 'CLASS' => $resultClass->getName()]);
                } else {
                    $constructorBody .= strtr('$this->NAME = $input["NAME"] ?? [];' . "\n", ['NAME' => $member->getName()]);
                }
            } else {
                $constructorBody .= strtr('$this->NAME = $input["NAME"];' . "\n", ['NAME' => $member->getName()]);
            }
        }
        $constructor->setBody($constructorBody);
    }

    /**
     * Add properties and getters.
     */
    private function addProperties(bool $root, StructureShape $shape, ClassType $class, PhpNamespace $namespace): void
    {
        foreach ($shape->getMembers() as $member) {
            $nullable = $returnType = null;
            $memberShape = $member->getShape();
            $property = $class->addProperty($member->getName())->setPrivate();
            [$returnType, $parameterType, $memberClassName] = $this->typeGenerator->getPhpType($memberShape, true);
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->addComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }

            if ($memberShape instanceof StructureShape) {
                $this->generateResultClass($memberShape);
            } elseif ($memberShape instanceof MapShape) {
                $mapKeyShape = $memberShape->getKey()->getShape();
                if ('string' !== $mapKeyShape->getType()) {
                    throw new \RuntimeException('Complex maps are not supported');
                }

                if (($valueShape = $memberShape->getValue()->getShape()) instanceof StructureShape) {
                    $this->generateResultClass($valueShape);
                }
                $nullable = false;
                $property->setValue([]);
            } elseif ($memberShape instanceof ListShape) {
                $memberShape->getMember()->getShape();

                if (($memberShape = $memberShape->getMember()->getShape()) instanceof StructureShape) {
                    $this->generateResultClass($memberShape);
                }

                $nullable = false;
                $property->setValue([]);
            } elseif ($member->isStreaming()) {
                $returnType = StreamableBodyInterface::class;
                $parameterType = StreamableBodyInterface::class;
                $memberClassName = null;
                $namespace->addUse(StreamableBodyInterface::class);
                $nullable = false;
            }

            if (null !== $memberClassName) {
                $namespace->addUse($memberClassName->getFqdn());
            }

            $method = $class->addMethod('get' . $member->getName())
                ->setReturnType($returnType)
                ->setBody(strtr('
                    INITIALIZE_CODE

                    return $this->NAME;
                ', [
                    'INITIALIZE_CODE' => $root ? '$this->initialize();' : '',
                    'NAME' => $member->getName(),
                ]));

            $nullable = $nullable ?? !$member->isRequired();
            if ($parameterType) {
                if ($parameterType !== $returnType && (null === $memberClassName || $memberClassName->getName() !== $parameterType)) {
                    if ($nullable) {
                        $parameterType = '?' . $parameterType;
                    }
                    $method->addComment('@return ' . $parameterType);
                }
            }
            $method->setReturnNullable($nullable);
        }
    }

    private function populateResult(StructureShape $shape, PhpNamespace $namespace, ClassType $class): void
    {
        // Parse headers
        $nonHeaders = [];
        $body = '';
        foreach ($shape->getMembers() as $member) {
            if ('header' !== $member->getLocation()) {
                $nonHeaders[$member->getName()] = $member;

                continue;
            }

            $locationName = strtolower($member->getLocationName() ?? $member->getName());
            $memberShape = $member->getShape();
            if ('timestamp' === $memberShape->getType()) {
                $body .= strtr('$this->NAME = isset($headers["LOCATION_NAME"][0]) ? new \DateTimeImmutable($headers["LOCATION_NAME"][0]) : null;' . "\n", [
                    'NAME' => $member->getName(),
                    'LOCATION_NAME' => $locationName,
                ]);
            } else {
                if (null !== $constant = $this->typeGenerator->getFilterConstant($memberShape)) {
                    $body .= strtr('$this->NAME = isset($headers["LOCATION_NAME"][0]) ? filter_var($headers["LOCATION_NAME"][0], FILTER) : null;' . "\n", [
                        'NAME' => $member->getName(),
                        'LOCATION_NAME' => $locationName,
                        'FILTER' => $constant,
                    ]);
                } else {
                    $body .= strtr('$this->NAME = $headers["LOCATION_NAME"][0] ?? null;' . "\n", [
                        'NAME' => $member->getName(),
                        'LOCATION_NAME' => $locationName,
                    ]);
                }
            }
        }

        foreach ($nonHeaders as $name => $member) {
            // "headers" are not "header"
            if ('headers' !== $member->getLocation()) {
                continue;
            }
            unset($nonHeaders[$name]);

            $locationName = strtolower($member->getLocationName() ?? $member->getName());
            $body .= strtr('
                $this->NAME = [];
                foreach ($headers as $name => $value) {
                    if (substr($name, 0, LENGTH) === "LOCATION_NAME") {
                        $this->NAME[$name] = $value[0];
                    }
                }
            ', [
                'NAME' => $member->getName(),
                'LENGTH' => \strlen($locationName),
                'LOCATION_NAME' => $locationName,
            ]);
        }

        // Prepend with $headers = ...
        if (!empty($body)) {
            $body = '$headers = $response->getHeaders(false);' . "\n\n" . $body;
        }

        $body .= "\n";
        $payloadProperty = $shape->getPayload();
        if (null !== $payloadProperty && $shape->getMember($payloadProperty)->isStreaming()) {
            // Make sure we can stream this.
            $namespace->addUse(StreamableBody::class);
            $body .= strtr('$this->PROPERTY_NAME = new StreamableBody($httpClient->stream($response));', ['PROPERTY_NAME' => $payloadProperty]);
        } else {
            $body .= $this->parserProvider->get($this->operation->getService())->generate($shape);
        }

        $method = $class->addMethod('populateResult')
            ->setReturnType('void')
            ->setProtected()
            ->setBody($body);
        $method->addParameter('response')->setType(ResponseInterface::class);
        $method->addParameter('httpClient')->setType(HttpClientInterface::class);
    }
}
