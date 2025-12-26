<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\ObjectShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\PopulatorGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassBuilder;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\Core\Result;
use AsyncAws\Core\Stream\ResultStream;
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
     * @var PopulatorGenerator
     */
    private $populatorGenerator;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, PopulatorGenerator $populatorGenerator)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->populatorGenerator = $populatorGenerator;
    }

    /**
     * Generate classes for the output. Ie, the result of the API call.
     */
    public function generate(Operation $operation): ClassName
    {
        if (null === $output = $operation->getOutput()) {
            throw new \LogicException(\sprintf('The operation "%s" does not have any output to generate', $operation->getName()));
        }

        return $this->generateResultClass($output, $operation);
    }

    private function generateResultClass(StructureShape $shape, Operation $operation): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getResult($shape);

        $classBuilder = $this->classRegistry->register($className->getFqdn());
        if (null !== $documentation = $shape->getDocumentationMain()) {
            $classBuilder->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        $classBuilder->addUse(Result::class);
        $classBuilder->setExtends(Result::class);
        $classBuilder->addUse(ResponseInterface::class);
        $classBuilder->addUse(HttpClientInterface::class);

        $this->populatorGenerator->generate($operation, $shape, $classBuilder, false, $operation->isEndpointOperation());
        $this->addUse($shape, $classBuilder);

        return $className;
    }

    /**
     * @param list<string> $addedFqdn
     */
    private function addUse(StructureShape $shape, ClassBuilder $classBuilder, array $addedFqdn = []): void
    {
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            if (!empty($memberShape->getEnum())) {
                $classBuilder->addUse($this->namespaceRegistry->getEnum($memberShape)->getFqdn());
            }

            if ($memberShape instanceof ObjectShape) {
                $fqdn = $this->namespaceRegistry->getObject($memberShape)->getFqdn();
                if (!\in_array($fqdn, $addedFqdn)) {
                    $addedFqdn[] = $fqdn;
                    $classBuilder->addUse($fqdn);
                }
            } elseif ($memberShape instanceof MapShape) {
                if (($valueShape = $memberShape->getValue()->getShape()) instanceof ObjectShape) {
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
                if (($memberShape = $memberShape->getMember()->getShape()) instanceof ObjectShape) {
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
