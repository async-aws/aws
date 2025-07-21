<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\CodeGenerator;

use AsyncAws\CodeGenerator\Definition\DocumentShape;
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
     * @param string[] $extra
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
                    $param = 'array<' . $className->getName() . '|array>';
                } elseif (!empty($listMemberShape->getEnum())) {
                    $classNames[] = $className = $this->namespaceRegistry->getEnum($listMemberShape);
                    $param = 'array<' . $className->getName() . '::*>';
                } else {
                    $param = $this->getNativePhpType($listMemberShape->getType()) . '[]';
                }
            } elseif ($memberShape instanceof MapShape) {
                $mapValueShape = $memberShape->getValue()->getShape();

                // is the map item an object?
                if ($mapValueShape instanceof StructureShape) {
                    $classNames[] = $className = $this->namespaceRegistry->getObject($mapValueShape);
                    $param = $className->getName() . '|array';
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
            } elseif ($memberShape instanceof DocumentShape) {
                $param = 'bool|string|int|float|null|list<mixed>|array<string, mixed>';
            } elseif ($member->isStreaming()) {
                $param = 'string|resource|(callable(int): string)|iterable<string>';
            } elseif ('timestamp' === $param = $memberShape->getType()) {
                $param = $isObject ? '\DateTimeImmutable' : '\DateTimeImmutable|string';
            } else {
                if (!empty($memberShape->getEnum())) {
                    $classNames[] = $className = $this->namespaceRegistry->getEnum($memberShape);
                    $param = $className->getName() . '::*';
                } else {
                    $param = $this->getNativePhpType($memberShape->getType());
                }
            }

            $phpdocMemberName = $member->getName();

            // Psalm treats a scalar type names as keywords and makes them lowercase even in array shape keys which are not types.
            // When a different case is needed, it requires using a quoted literal instead of an identifier.
            // TODO remove that code once https://github.com/vimeo/psalm/issues/10008 is solved (in a release)
            if (\in_array(strtolower($phpdocMemberName), ['bool', 'null', 'int', 'float', 'double', 'scalar']) && $phpdocMemberName !== strtolower($phpdocMemberName)) {
                $phpdocMemberName = "'" . $phpdocMemberName . "'";
            }

            if ($nullable) {
                $body[] = \sprintf('  %s?: %s,', $phpdocMemberName, 'null|' . $param);
            } elseif ($allNullable) {
                // For input objects, the constructor allows to omit all members to set them later. But when provided,
                // they should respect the nullability of the member.
                $body[] = \sprintf('  %s?: %s,', $phpdocMemberName, $param);
            } else {
                $body[] = \sprintf('  %s: %s,', $phpdocMemberName, $param);
            }
        }
        $body = array_merge($body, $extra);
        $body[] = '}' . ($alternateClass ? '|' . $shapeClassName->getName() : '') . ' $input';

        return [implode("\n", $body), $classNames];
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
            if ('::*' === substr($doc, -3)) {
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

        if ($shape instanceof DocumentShape) {
            return ['bool|string|int|float|null|array', 'bool|string|int|float|null|list<mixed>|array<string, mixed>', []];
        }

        $type = $doc = $this->getNativePhpType($shape->getType());
        if (!empty($shape->getEnum())) {
            $memberClassNames[] = $memberClassName = $this->namespaceRegistry->getEnum($shape);

            $doc = $memberClassName->getName() . '::*';
        }

        return [$type, $doc, $memberClassNames];
    }

    private function getNativePhpType(string $parameterType): string
    {
        switch ($parameterType) {
            case 'list':
            case 'structure':
            case 'map':
                return 'array';
            case 'string':
            case 'blob':
                return 'string';
            case 'long':
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
                throw new \RuntimeException(\sprintf('Type %s is not yet implemented', $parameterType));
        }
    }
}
