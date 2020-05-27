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

    public function generateDocblock(StructureShape $shape, ClassName $className, bool $alternateClass = true, bool $allNullable = false, bool $isObject = false, array $extra = []): string
    {
        if (empty($shape->getMembers()) && empty($extra)) {
            // No input array
            return '@param array' . ($alternateClass ? '|' . $className->getName() : '') . ' $input';
        }

        $body = ['@param array{'];
        foreach ($shape->getMembers() as $member) {
            $nullable = !$member->isRequired();
            $memberShape = $member->getShape();

            if ($memberShape instanceof StructureShape) {
                $param = '\\' . $this->namespaceRegistry->getObject($memberShape)->getFqdn() . '|array';
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // is the list item an object?
                if ($listMemberShape instanceof StructureShape) {
                    $param = '\\' . $this->namespaceRegistry->getObject($listMemberShape)->getFqdn() . '[]';
                } elseif (!empty($listMemberShape->getEnum())) {
                    $param = 'list<\\' . $this->namespaceRegistry->getEnum($listMemberShape)->getFqdn() . '::*>';
                } else {
                    $param = $this->getNativePhpType($listMemberShape->getType()) . '[]';
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                // is the map item an object?
                if ($mapValueShape instanceof StructureShape) {
                    $param = '\\' . $this->namespaceRegistry->getObject($mapValueShape)->getFqdn();
                } elseif (!empty($mapValueShape->getEnum())) {
                    $param = '\\' . $this->namespaceRegistry->getEnum($mapValueShape)->getFqdn() . '::*';
                } else {
                    $param = $this->getNativePhpType($mapValueShape->getType());
                }
                $mapKeyShape = $memberShape->getKey()->getShape();
                if (!empty($mapKeyShape->getEnum())) {
                    $param = 'array<\\' . $this->namespaceRegistry->getEnum($mapKeyShape)->getFqdn() . '::*, ' . $param . '>';
                } else {
                    $param = 'array<string, ' . $param . '>';
                }
            } elseif ($member->isStreaming()) {
                $param = 'string|resource|callable|iterable';
            } elseif ('timestamp' === $param = $memberShape->getType()) {
                $param = $isObject ? '\DateTimeImmutable' : '\DateTimeImmutable|string';
            } else {
                if (!empty($memberShape->getEnum())) {
                    $param = '\\' . $this->namespaceRegistry->getEnum($memberShape)->getFqdn() . '::*';
                } else {
                    $param = $this->getNativePhpType($param);
                }
            }

            if ($allNullable || $nullable) {
                if ($isObject) {
                    $body[] = sprintf('  %s?: %s,', $member->getName(), 'null|' . $param);
                } else {
                    $body[] = sprintf('  %s?: %s,', $member->getName(), $param);
                }
            } else {
                $body[] = sprintf('  %s: %s,', $member->getName(), $param);
            }
        }
        $body = \array_merge($body, $extra);
        $body[] = '}' . ($alternateClass ? '|' . $className->getName() : '') . ' $input';

        return \implode("\n", $body);
    }

    /**
     * Return php type information for the given shape.
     *
     * @return array{string, string, ClassName[]} [typeHint value, docblock representation, ClassName related]
     */
    public function getPhpType(Shape $shape): array
    {
        $memberClassNames = [];
        if ($shape instanceof StructureShape) {
            $memberClassNames[] = $className = $this->namespaceRegistry->getObject($shape);

            return [$className->getFqdn(), $className->getName(), $memberClassNames];
        }

        if ($shape instanceof ListShape) {
            $listMemberShape = $shape->getMember()->getShape();
            if ($listMemberShape instanceof StructureShape) {
                $memberClassNames[] = $className = $this->namespaceRegistry->getObject($listMemberShape);

                return ['array', $className->getName() . '[]', $memberClassNames];
            }
            if ($listMemberShape instanceof ListShape) {
                $listMemberShapeLevel2 = $listMemberShape->getMember()->getShape();
                if ($listMemberShapeLevel2 instanceof StructureShape) {
                    $memberClassNames[] = $className = $this->namespaceRegistry->getObject($listMemberShapeLevel2);

                    return ['array', $className->getName() . '[][]', $memberClassNames];
                }

                if (!empty($listMemberShapeLevel2->getEnum())) {
                    $memberClassNames[] = $memberClassName = $this->namespaceRegistry->getEnum($listMemberShapeLevel2);
                    $doc = 'list<list<' . $memberClassName->getName() . '::*>>';
                } else {
                    $doc = $this->getNativePhpType($listMemberShapeLevel2->getType()) . '[][]';
                }

                return ['array', $doc, $memberClassNames];
            }

            if (!empty($listMemberShape->getEnum())) {
                $memberClassNames[] = $memberClassName = $this->namespaceRegistry->getEnum($listMemberShape);
                $doc = 'list<' . $memberClassName->getName() . '::*>';
            } else {
                $doc = $this->getNativePhpType($listMemberShape->getType()) . '[]';
            }

            return ['array', $doc, $memberClassNames];
        }

        if ($shape instanceof MapShape) {
            $mapValueShape = $shape->getValue()->getShape();
            if ($mapValueShape instanceof StructureShape) {
                $memberClassNames[] = $className = $this->namespaceRegistry->getObject($mapValueShape);
                $doc = $className->getName();
            } elseif (!empty($mapValueShape->getEnum())) {
                $memberClassNames[] = $memberClassName = $this->namespaceRegistry->getEnum($mapValueShape);
                $doc = $memberClassName->getName() . '::*';
            } else {
                $doc = $this->getNativePhpType($mapValueShape->getType());
            }

            $mapKeyShape = $shape->getKey()->getShape();
            $keyType = null;
            if (!empty($mapKeyShape->getEnum())) {
                $memberClassNames[] = $memberClassName = $this->namespaceRegistry->getEnum($mapKeyShape);
                $doc = 'array<' . $memberClassName->getName() . '::*, ' . $doc . '>';
            } else {
                $doc = 'array<string, ' . $doc . '>';
            }

            return ['array', $doc, $memberClassNames];
        }

        $type = $doc = $this->getNativePhpType($shape->getType());
        if (!empty($shape->getEnum())) {
            $memberClassNames[] = $memberClassName = $this->namespaceRegistry->getEnum($shape);

            $doc = $memberClassName->getName() . '::*';
        }

        return [$type, $doc, $memberClassNames];
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
                return '\DateTimeImmutable';
            default:
                throw new \RuntimeException(sprintf('Type %s is not yet implemented', $parameterType));
        }
    }
}
