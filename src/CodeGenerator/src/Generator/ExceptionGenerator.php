<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ExceptionShape;
use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Generator\CodeGenerator\PopulatorGenerator;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\Core\Exception\Http\ClientException;
use AsyncAws\Core\Exception\Http\ServerException;

/**
 * Generate API client methods and result classes.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ExceptionGenerator
{
    /**
     * @var ClassName[]
     */
    private $generated = [];

    /**
     * @var ClassRegistry
     */
    private $classRegistry;

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

    public function generate(Operation $operation, ExceptionShape $shape): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getException($shape);

        $classBuilder = $this->classRegistry->register($className->getFqdn());
        $classBuilder->setFinal();
        if (null !== $documentation = $shape->getDocumentation()) {
            $classBuilder->addComment(GeneratorHelper::parseDocumentation($documentation, false));
        }

        if ($shape->isSenderFault()) {
            $classBuilder->addExtend(ClientException::class);
            $classBuilder->addUse(ClientException::class);
        } else {
            $classBuilder->addExtend(ServerException::class);
            $classBuilder->addUse(ServerException::class);
        }

        if (0 < \count($members = $shape->getMembers())) {
            $this->populatorGenerator->generate($operation, $shape, $classBuilder, true);
        }

        return $className;
    }
}
