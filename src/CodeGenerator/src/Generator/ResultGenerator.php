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
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\StreamableBody;
use AsyncAws\Core\StreamableBodyInterface;
use Nette\InvalidStateException;
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
     * @var ObjectGenerator
     */
    private $objectGenerator;

    /**
     * @var EnumGenerator
     */
    private $enumGenerator;

    /**
     * @var ParserProvider
     */
    private $parserProvider;

    /**
     * @var Operation
     */
    private $operation;

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter, ObjectGenerator $objectGenerator, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($this->namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($this->namespaceRegistry, $fileWriter);
        $this->parserProvider = new ParserProvider($this->namespaceRegistry);
        $this->objectGenerator = $objectGenerator;
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

        return $this->generateResultClass($output);
    }

    private function generateResultClass(StructureShape $shape): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getResult($shape);

        $namespace = new PhpNamespace($className->getNamespace());
        $class = $namespace->addClass($className->getName());

        $namespace->addUse(Result::class);
        $class->addExtend(Result::class);

        $namespace->addUse(ResponseInterface::class);
        $namespace->addUse(HttpClientInterface::class);
        $this->populateResult($shape, $namespace, $class);

        $this->addProperties($shape, $class, $namespace);
        $this->addUse($shape, $namespace);

        $this->fileWriter->write($namespace);

        return $className;
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
            [$returnType, $parameterType, $memberClassName] = $this->typeGenerator->getPhpType($memberShape);

            if (!empty($memberShape->getEnum())) {
                $this->enumGenerator->generate($memberShape);
            }

            if ($memberShape instanceof StructureShape) {
                $this->objectGenerator->generate($memberShape);
            } elseif ($memberShape instanceof MapShape) {
                $mapKeyShape = $memberShape->getKey()->getShape();
                if ('string' !== $mapKeyShape->getType()) {
                    throw new \RuntimeException('Complex maps are not supported');
                }

                if (($valueShape = $memberShape->getValue()->getShape()) instanceof StructureShape) {
                    $this->objectGenerator->generate($valueShape);
                }
                if (!empty($valueShape->getEnum())) {
                    $this->enumGenerator->generate($valueShape);
                }

                $nullable = false;
                $property->setValue([]);
            } elseif ($memberShape instanceof ListShape) {
                $memberShape->getMember()->getShape();

                if (($memberShape = $memberShape->getMember()->getShape()) instanceof StructureShape) {
                    $this->objectGenerator->generate($memberShape);
                }
                if (!empty($memberShape->getEnum())) {
                    $this->enumGenerator->generate($memberShape);
                }

                $nullable = false;
                $property->setValue([]);
            } elseif ($member->isStreaming()) {
                $returnType = StreamableBodyInterface::class;
                $parameterType = StreamableBodyInterface::class;
                $memberClassName = null;
                $nullable = false;
            }

            if (null !== $memberClassName) {
                try {
                    $namespace->addUse($memberClassName->getFqdn());
                } catch (InvalidStateException $e) {
                }
            }

            $method = $class->addMethod('get' . $member->getName())
                ->setReturnType($returnType)
                ->setBody(strtr('
                    $this->initialize();

                    return $this->NAME;
                ', [
                    'NAME' => $member->getName(),
                ]));

            $nullable = $nullable ?? !$member->isRequired();
            if ($parameterType && $parameterType !== $returnType && (null === $memberClassName || $memberClassName->getName() !== $parameterType)) {
                $method->addComment('@return ' . $parameterType . ($nullable ? '|null' : ''));
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

        // This will catch arbitrary values that exists in undefined "headers"
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
            $body = '$headers = $response->getHeaders();' . "\n\n" . $body;
        }

        // Find status code
        foreach ($nonHeaders as $name => $member) {
            if ('statusCode' === $member->getLocation()) {
                $body = '$this->' . $member->getName() . ' = $response->getStatusCode();' . "\n" . $body;
            }
        }

        $body .= "\n";
        $payloadProperty = $shape->getPayload();
        if (null !== $payloadProperty && $shape->getMember($payloadProperty)->isStreaming()) {
            // Make sure we can stream this.
            $namespace->addUse(StreamableBody::class);
            $body .= strtr('$this->PROPERTY_NAME = $response->toStream();', ['PROPERTY_NAME' => $payloadProperty]);
        } else {
            $body .= $this->parserProvider->get($this->operation->getService())->generate($shape);
        }

        $namespace->addUse(Response::class);
        $method = $class->addMethod('populateResult')
            ->setReturnType('void')
            ->setProtected()
            ->setBody($body);
        $method->addParameter('response')->setType(Response::class);
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
