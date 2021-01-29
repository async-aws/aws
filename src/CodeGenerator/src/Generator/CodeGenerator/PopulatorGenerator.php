<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\CodeGenerator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\EnumGenerator;
use AsyncAws\CodeGenerator\Generator\GeneratorHelper;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\ObjectGenerator;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\CodeGenerator\Generator\ResponseParser\ParserProvider;
use AsyncAws\Core\Response;
use AsyncAws\Core\Stream\ResponseBodyStream;
use AsyncAws\Core\Stream\ResultStream;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Generate method and properties to populate object from AWS response.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class PopulatorGenerator
{
    /**
     * @var ObjectGenerator
     */
    private $objectGenerator;

    /**
     * @var EnumGenerator
     */
    private $enumGenerator;

    /**
     * @var TypeGenerator
     */
    private $typeGenerator;

    /**
     * @var ParserProvider
     */
    private $parserProvider;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, ObjectGenerator $objectGenerator, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null)
    {
        $this->objectGenerator = $objectGenerator;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($classRegistry, $namespaceRegistry);
        $this->parserProvider = new ParserProvider($namespaceRegistry, $this->typeGenerator);
    }

    public function generate(Operation $operation, StructureShape $shape, ClassBuilder $classBuilder, bool $forException = false): void
    {
        $this->generatePopulator($operation, $shape, $classBuilder, $forException);
        $this->generateProperties($shape, $classBuilder, $forException);
    }

    /**
     * Add properties and getters.
     */
    private function generateProperties(StructureShape $shape, ClassBuilder $classBuilder, bool $forException): void
    {
        foreach ($shape->getMembers() as $member) {
            $propertyName = GeneratorHelper::normalizeName($member->getName());
            if ($forException && \in_array($propertyName, ['code', 'message'], true)) {
                continue;
            }

            $nullable = $returnType = null;
            $memberShape = $member->getShape();
            $property = $classBuilder->addProperty($propertyName)->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentation()) {
                $property->setComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }
            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($memberShape);
            foreach ($memberClassNames as $memberClassName) {
                $classBuilder->addUse($memberClassName->getFqdn());
            }

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
                if (!empty($mapKeyShape->getEnum())) {
                    $this->enumGenerator->generate($mapKeyShape);
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
                $returnType = ResultStream::class;
                $parameterType = ResultStream::class;
                $memberClassNames = [];
                $nullable = false;
            }

            $method = $classBuilder->addMethod('get' . \ucfirst(GeneratorHelper::normalizeName($member->getName())))
                ->setReturnType($returnType);

            $deprecation = '';
            if ($member->isDeprecated()) {
                $method->addComment('@deprecated');
                $deprecation = strtr('@trigger_error(\sprintf(\'The property "NAME" of "%s" is deprecated by AWS.\', __CLASS__), E_USER_DEPRECATED);', ['NAME' => $propertyName]);
            }

            $method->setBody($deprecation . strtr('
                    INITIALIZER

                    return $this->PROPERTY;
                ', [
                'PROPERTY' => $propertyName,
                'INITIALIZER' => $forException ? '' : '$this->initialize();',
            ]));

            $nullable = $nullable ?? !$member->isRequired();
            if ($parameterType && $parameterType !== $returnType && (empty($memberClassNames) || $memberClassNames[0]->getName() !== $parameterType)) {
                $method->addComment('@return ' . $parameterType . ($nullable ? '|null' : ''));
            }
            $method->setReturnNullable($nullable);
        }
    }

    private function generatePopulator(Operation $operation, StructureShape $shape, ClassBuilder $classBuilder, bool $forException): void
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
            $propertyName = GeneratorHelper::normalizeName($member->getName());
            $memberShape = $member->getShape();
            if ('timestamp' === $memberShape->getType()) {
                $body .= strtr('$this->PROPERTY_NAME = isset($headers["LOCATION_NAME"][0]) ? new \DateTimeImmutable($headers["LOCATION_NAME"][0]) : null;' . "\n", [
                    'PROPERTY_NAME' => $propertyName,
                    'LOCATION_NAME' => $locationName,
                ]);
            } else {
                if (null !== $constant = $this->typeGenerator->getFilterConstant($memberShape)) {
                    $body .= strtr('$this->PROPERTY_NAME = isset($headers["LOCATION_NAME"][0]) ? filter_var($headers["LOCATION_NAME"][0], FILTER) : null;' . "\n", [
                        'PROPERTY_NAME' => $propertyName,
                        'LOCATION_NAME' => $locationName,
                        'FILTER' => $constant,
                    ]);
                } else {
                    $body .= strtr('$this->PROPERTY_NAME = $headers["LOCATION_NAME"][0] ?? null;' . "\n", [
                        'PROPERTY_NAME' => $propertyName,
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
            $propertyName = GeneratorHelper::normalizeName($member->getName());
            $body .= strtr('
                $this->PROPERTY_NAME = [];
                foreach ($headers as $name => $value) {
                    if (substr($name, 0, LENGTH) === "LOCATION_NAME") {
                        $this->PROPERTY_NAME[substr($name, LENGTH)] = $value[0];
                    }
                }
            ', [
                'PROPERTY_NAME' => $propertyName,
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
                $body = '$this->' . GeneratorHelper::normalizeName($member->getName()) . ' = $response->getStatusCode();' . "\n" . $body;
            }
        }

        $body .= "\n";
        $payloadProperty = $shape->getPayload();
        if (null !== $payloadProperty && $shape->getMember($payloadProperty)->isStreaming()) {
            // Make sure we can stream this.
            $classBuilder->addUse(ResponseBodyStream::class);
            $body .= strtr('$this->PROPERTY_NAME = $response->toStream();', ['PROPERTY_NAME' => GeneratorHelper::normalizeName($payloadProperty)]);
        } else {
            $parserResult = $this->parserProvider->get($operation->getService())->generate($shape, !$forException);
            $body .= $parserResult->getBody();

            foreach ($parserResult->getUsedClasses() as $className) {
                $classBuilder->addUse($className->getFqdn());
            }
            $classBuilder->setMethods($parserResult->getExtraMethods());
        }
        if (empty(trim($body))) {
            return;
        }

        $method = $classBuilder->addMethod('populateResult')
            ->setReturnType('void')
            ->setBody($body)
            ->setProtected();
        if ($forException) {
            $method->addParameter('response')->setType(ResponseInterface::class);
            $classBuilder->addUse(ResponseInterface::class);
        } else {
            $method->addParameter('response')->setType(Response::class);
            $classBuilder->addUse(Response::class);
        }
    }
}
