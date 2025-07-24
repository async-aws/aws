<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\CodeGenerator;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
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
     * @var RequirementsRegistry
     */
    private $requirementsRegistry;

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

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry, ObjectGenerator $objectGenerator, ?TypeGenerator $typeGenerator = null, ?EnumGenerator $enumGenerator = null, ?ParserProvider $parserProvider = null)
    {
        $this->objectGenerator = $objectGenerator;
        $this->requirementsRegistry = $requirementsRegistry;
        $this->typeGenerator = $typeGenerator ?? new TypeGenerator($namespaceRegistry);
        $this->enumGenerator = $enumGenerator ?? new EnumGenerator($classRegistry, $namespaceRegistry);
        $this->parserProvider = $parserProvider ?? new ParserProvider($namespaceRegistry, $requirementsRegistry, $this->typeGenerator);
    }

    public function generate(Operation $operation, StructureShape $shape, ClassBuilder $classBuilder, bool $forException = false, bool $forEndpoint = false): void
    {
        $this->generatePopulator($operation, $shape, $classBuilder, $forException);
        $this->generateProperties($shape, $classBuilder, $forException, $forEndpoint);
    }

    /**
     * Add properties and getters.
     */
    private function generateProperties(StructureShape $shape, ClassBuilder $classBuilder, bool $forException, bool $forEndpoint): void
    {
        foreach ($shape->getMembers() as $member) {
            $propertyName = GeneratorHelper::normalizeName($member->getName());
            if ($forException && \in_array($propertyName, ['code', 'message'], true)) {
                continue;
            }

            $nullable = $returnType = null;
            $memberShape = $member->getShape();
            $property = $classBuilder->addProperty($propertyName)->setPrivate();
            if (null !== $propertyDocumentation = $memberShape->getDocumentationMember()) {
                $property->setComment(GeneratorHelper::parseDocumentation($propertyDocumentation));
            }
            [$returnType, $parameterType, $memberClassNames] = $this->typeGenerator->getPhpType($memberShape, false);
            foreach ($memberClassNames as $memberClassName) {
                $classBuilder->addUse($memberClassName->getFqdn());
            }

            if (!empty($memberShape->getEnum())) {
                $this->enumGenerator->generate($memberShape);
            }

            if ($memberShape instanceof StructureShape) {
                $this->objectGenerator->generate($memberShape, false, false);
            } elseif ($memberShape instanceof MapShape) {
                $mapKeyShape = $memberShape->getKey()->getShape();
                if ('string' !== $mapKeyShape->getType()) {
                    throw new \RuntimeException('Complex maps are not supported');
                }
                if (!empty($mapKeyShape->getEnum())) {
                    $this->enumGenerator->generate($mapKeyShape);
                }

                if (($valueShape = $memberShape->getValue()->getShape()) instanceof StructureShape) {
                    $this->objectGenerator->generate($valueShape, false, false);
                }
                if (!empty($valueShape->getEnum())) {
                    $this->enumGenerator->generate($valueShape);
                }

                $nullable = false;
            } elseif ($memberShape instanceof ListShape) {
                $memberShape = $memberShape->getMember()->getShape();

                $this->generateListShapeMemberShape($memberShape, $forEndpoint);

                if ($memberShape instanceof StructureShape && $forEndpoint && 'endpoints' === $propertyName) {
                    $forEndpoint = false;
                }

                $nullable = false;
            } elseif ($memberShape instanceof DocumentShape) {
                $nullable = false; // the type is already nullable, not need to add an extra union
            } elseif ($member->isStreaming()) {
                $returnType = ResultStream::class;
                $parameterType = 'ResultStream';
                $memberClassNames = [];
                $classBuilder->addUse(ResultStream::class);
                $nullable = false;
            }

            $method = $classBuilder->addMethod('get' . ucfirst(GeneratorHelper::normalizeName($member->getName())))
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
            if ($parameterType) {
                $property->addComment('@var ' . $parameterType . ($nullable ? '|null' : ''));
            }
        }

        if ($forEndpoint) {
            throw new \LogicException('The EndpointResult object does not contains the required methods');
        }
    }

    private function generatePopulator(Operation $operation, StructureShape $shape, ClassBuilder $classBuilder, bool $forException): void
    {
        // Parse headers
        $nonHeaders = [];
        $body = '';
        foreach ($shape->getMembers() as $member) {
            // Avoid conflicts with PHP properties. Those AWS members are included in the AWSError anyway.
            if ($forException && \in_array(strtolower($member->getName()), ['code', 'message'])) {
                continue;
            }

            if ('header' !== $member->getLocation()) {
                $nonHeaders[$member->getName()] = $member;

                continue;
            }

            $locationName = strtolower($member->getLocationName() ?? $member->getName());
            $propertyName = GeneratorHelper::normalizeName($member->getName());
            $memberShape = $member->getShape();
            switch ($memberShape->getType()) {
                case 'timestamp':
                    $propertyValue = 'new \DateTimeImmutable($headers["LOCATION_NAME"][0])';

                    break;
                case 'integer':
                case 'long':
                    $propertyValue = '(int) $headers["LOCATION_NAME"][0]';

                    break;
                case 'boolean':
                    $this->requirementsRegistry->addRequirement('ext-filter');

                    $propertyValue = 'filter_var($headers["LOCATION_NAME"][0], FILTER_VALIDATE_BOOLEAN)';

                    break;
                case 'string':
                    $propertyValue = '$headers["LOCATION_NAME"][0]';

                    break;
                default:
                    throw new \RuntimeException(\sprintf('Type %s is not yet implemented', $memberShape->getType()));
            }
            if (!$member->isRequired()) {
                if ('$headers["LOCATION_NAME"][0]' === $propertyValue) {
                    $propertyValue .= '?? null';
                } else {
                    $propertyValue = 'isset($headers["LOCATION_NAME"][0]) ? ' . $propertyValue . ' : null';
                }
            }

            $body .= strtr('$this->PROPERTY_NAME = ' . $propertyValue . ';' . "\n", [
                'PROPERTY_NAME' => $propertyName,
                'LOCATION_NAME' => $locationName,
            ]);
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

    private function generateListShapeMemberShape(Shape $memberShape, bool $forEndpoint): void
    {
        if ($memberShape instanceof StructureShape) {
            $this->objectGenerator->generate($memberShape, $forEndpoint, false);
        } elseif ($memberShape instanceof ListShape) {
            $this->generateListShapeMemberShape($memberShape->getMember()->getShape(), $forEndpoint);
        }
        if (!empty($memberShape->getEnum())) {
            $this->enumGenerator->generate($memberShape);
        }
    }
}
