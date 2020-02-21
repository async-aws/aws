<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\CodeGenerator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;

/**
 * Small methods that might be useful when generating code.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class TypeGenerator
{
    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    public function __construct(NamespaceRegistry $namespaceRegistry)
    {
        $this->namespaceRegistry = $namespaceRegistry;
    }

    public function generateDocblock(StructureShape $shape, ClassName $className, bool $alternateClass = true, bool $allNullable = false, bool $isResult = false): string
    {
        if (empty($shape->getMembers())) {
            // No input array
            return '@param array' . ($alternateClass ? '|' . $className->getName() : '') . ' $input';
        }

        $classNameFactory = [$this->namespaceRegistry, $isResult ? 'getResult' : 'getInput'];

        $body = ['@param array{'];
        foreach ($shape->getMembers() as $member) {
            $nullable = !$member->isRequired();
            $memberShape = $member->getShape();

            if ($memberShape instanceof StructureShape) {
                $param = '\\' . $classNameFactory($memberShape)->getFqdn() . '|array';
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // is the list item an object?
                if ($listMemberShape instanceof StructureShape) {
                    $param = '\\' . $classNameFactory($listMemberShape)->getFqdn() . '[]';
                } else {
                    $param = $this->getNativePhpType($listMemberShape->getType()) . '[]';
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapMemberShape = $memberShape->getValue()->getShape();

                // is the map item an object?
                if ($mapMemberShape instanceof StructureShape) {
                    $param = '\\' . $classNameFactory($mapMemberShape)->getFqdn() . '[]';
                } else {
                    $param = $this->getNativePhpType($mapMemberShape->getType()) . '[]';
                }
            } elseif ($member->isStreaming()) {
                $param = 'string|resource|\Closure';
            } elseif ('timestamp' === $param = $memberShape->getType()) {
                $param = $isResult ? '\DateTimeInterface' : '\DateTimeInterface|string';
            } else {
                $param = $this->getNativePhpType($param);
            }

            if ($allNullable || $nullable) {
                if ($isResult) {
                    $body[] = sprintf('  %s: %s,', $member->getName(), \strpos($param, '|') ? 'null|' . $param : '?' . $param);
                } else {
                    $body[] = sprintf('  %s?: %s,', $member->getName(), $param);
                }
            } else {
                $body[] = sprintf('  %s: %s,', $member->getName(), $param);
            }
        }
        $body[] = '}' . ($alternateClass ? '|' . $className->getName() : '') . ' $input';

        return \implode("\n", $body);
    }

    /**
     * Return php type information for the given shape.
     *
     * @return array{string, string, ?ClassName} [typeHint value, docblock representation, ClassName related]
     */
    public function getPhpType(Shape $shape, bool $isResult = false): array
    {
        $classNameFactory = [$this->namespaceRegistry, $isResult ? 'getResult' : 'getInput'];
        if ($shape instanceof StructureShape) {
            $className = $classNameFactory($shape);

            return [$className->getFqdn(), $className->getName(), $className];
        }

        if ($shape instanceof ListShape) {
            $listMemberShape = $shape->getMember()->getShape();
            if ($listMemberShape instanceof StructureShape) {
                $className = $classNameFactory($listMemberShape);

                return ['array', $className->getName() . '[]', $className];
            }

            return ['array', $this->getNativePhpType($listMemberShape->getType()) . '[]', null];
        }

        if ($shape instanceof MapShape) {
            $listMemberShape = $shape->getValue()->getShape();
            if ($listMemberShape instanceof StructureShape) {
                $className = $classNameFactory($listMemberShape);

                return ['array', $className->getName() . '[]', $className];
            }

            return ['array', $this->getNativePhpType($listMemberShape->getType()) . '[]', null];
        }

        return [$type = $this->getNativePhpType($shape->getType()), $type, null];
    }

    public function getFilterConstant(Shape $shape): ?string
    {
        switch ($shape->getType()) {
            case 'integer':
                return 'FILTER_VALIDATE_INT';
            case 'boolean':
                return 'FILTER_VALIDATE_BOOLEAN';
            case 'string':
            default:
                return null;
        }
    }

    private function getNativePhpType(string $parameterType): string
    {
        switch ($parameterType) {
            case 'list':
            case 'structure':
            case 'map':
                return 'array';
            case 'long':
            case 'string':
            case 'blob':
                return 'string';
            case 'integer':
                return 'int';
            case 'float':
            case 'double':
                return 'float';
            case 'boolean':
                return 'bool';
            case 'timestamp':
                return '\DateTimeInterface';
            default:
                throw new \RuntimeException(sprintf('Type %s is not yet implemented', $parameterType));
        }
    }
}
