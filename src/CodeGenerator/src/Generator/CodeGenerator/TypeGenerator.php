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

    /**
     * Return docblock information for the given shape.
     *
     * @return array{string, ClassName[]} [docblock representation, ClassName related]
     */
    public function generateDocblock(StructureShape $shape, ClassName $shapeClassName, bool $alternateClass = true, bool $allNullable = false, bool $isObject = false, array $extra = []): array
    {
        $classNames = [];
        if ($alternateClass) {
            $classNames[] = $shapeClassName;
        }
        if (empty($shape->getMembers()) && empty($extra)) {
            // No input array
            return ['@param array' . ($alternateClass ? '|' . $shapeClassName->getName() : '') . ' $input', $classNames];
        }

        $body = ['@param array{'];
        foreach ($shape->getMembers() as $member) {
            $nullable = !$member->isRequired();
            $memberShape = $member->getShape();

            if ($memberShape instanceof StructureShape) {
                $classNames[] = $className = $this->namespaceRegistry->getObject($memberShape);
                $param = $className->getName() . '|array';
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // is the list item an object?
                if ($listMemberShape instanceof StructureShape) {
                    $classNames[] = $className = $this->namespaceRegistry->getObject($listMemberShape);
                    $param = $className->getName() . '[]';
                } elseif (!empty($listMemberShape->getEnum())) {
                    $classNames[] = $className = $this->namespaceRegistry->getEnum($listMemberShape);
                    $param = 'list<' . $className->getName() . '::*>';
                } else {
                    $param = $this->getNativePhpType($listMemberShape->getType()) . '[]';
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                // is the map item an object?
                if ($mapValueShape instanceof StructureShape) {
                    $classNames[] = $className = $this->namespaceRegistry->getObject($mapValueShape);
                    $param = $className->getName();
                } elseif (!empty($mapValueShape->getEnum())) {
                    $classNames[] = $className = $this->namespaceRegistry->getEnum($mapValueShape);
                    $param = $className->getName() . '::*';
                } else {
                    $param = $this->getNativePhpType($mapValueShape->getType());
                }
                $mapKeyShape = $memberShape->getKey()->getShape();
                if (!empty($mapKeyShape->getEnum())) {
                    $classNames[] = $className = $this->namespaceRegistry->getEnum($mapKeyShape);
                    $param = 'array<' . $className->getName() . '::*, ' . $param . '>';
                } else {
                    $param = 'array<string, ' . $param . '>';
                }
            } elseif ($member->isStreaming()) {
                $param = 'string|resource|callable|iterable';
            } elseif ('timestamp' === $param = $memberShape->getType()) {
                $param = $isObject ? '\DateTimeImmutable' : '\DateTimeImmutable|string';
            } else {
                if (!empty($memberShape->getEnum())) {
                    $classNames[] = $className = $this->namespaceRegistry->getEnum($memberShape);
                    $param = $className->getName() . '::*';
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
        $body[] = '}' . ($alternateClass ? '|' . $shapeClassName->getName() : '') . ' $input';

        return [\implode("\n", $body), $classNames];
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
            [$type, $doc, $memberClassNames] = $this->getPhpType($listMemberShape);
            if ('::*' === \substr($doc, -3)) {
                $doc = "list<$doc>";
            } else {
                $doc .= '[]';
            }

            return ['array', $doc, $memberClassNames];
        }

        if ($shape instanceof MapShape) {
            $mapKeyShape = $shape->getKey()->getShape();
            $mapValueShape = $shape->getValue()->getShape();
            [$type, $doc, $memberClassNames] = $this->getPhpType($mapValueShape);
            if (!empty($mapKeyShape->getEnum())) {
                $memberClassNames[] = $memberClassName = $this->namespaceRegistry->getEnum($mapKeyShape);
                $doc = "array<{$memberClassName->getName()}::*, $doc>";
            } else {
                $doc = "array<string, $doc>";
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
