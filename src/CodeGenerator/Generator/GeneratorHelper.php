<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * Small methods that might be useful when generating code.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class GeneratorHelper
{
    public static function toPhpType(string $parameterType): string
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

    /**
     * Make sure we dont use a class name like Trait or Object.
     */
    public static function safeClassName(string $name): string
    {
        if (\in_array($name, ['Object', 'Class', 'Trait'])) {
            return 'Aws' . $name;
        }

        return $name;
    }

    /**
     * This is will produce the same result as `var_export` but on only one line.
     */
    public static function printArray(array $data): string
    {
        $output = '[';
        foreach ($data as $name => $value) {
            $output .= sprintf('%s => %s,', (\is_int($name) ? $name : '"' . $name . '"'), \is_array($value) ? self::printArray($value) : ("'" . $value . "'"));
        }
        $output .= ']';

        return $output;
    }

    public static function addMethodComment(StructureShape $inputShape, string $baseNamespace, bool $allNullable = false, bool $inputSafe = false): array
    {
        $body = [];
        foreach ($inputShape->getMembers() as $member) {
            $nullable = !$member->isRequired();
            $memberShape = $member->getShape();

            if ($memberShape instanceof StructureShape) {
                $param = '\\' . $baseNamespace . '\\' . self::safeClassName($memberShape->getName()) . '|array';
            } elseif ($memberShape instanceof ListShape) {
                $listMemberShape = $memberShape->getMember()->getShape();

                // is the list item an object?
                if ($listMemberShape instanceof StructureShape) {
                    $param = '\\' . $baseNamespace . '\\' . self::safeClassName($listMemberShape->getName()) . '[]';
                } else {
                    $param = self::toPhpType($listMemberShape->getType()) . '[]';
                }
            } elseif ($member->isStreaming()) {
                $param = 'string|resource|\Closure';
            } elseif ('timestamp' === $param = $memberShape->getType()) {
                $param = $inputSafe ? '\DateTimeInterface' : '\DateTimeInterface|string';
            } else {
                $param = self::toPhpType($param);
            }

            if ($allNullable || $nullable) {
                if ($inputSafe) {
                    $body[] = sprintf('  %s: %s,', $member->getName(), \strpos($param, '|') ? 'null|' . $param : '?' . $param);
                } else {
                    $body[] = sprintf('  %s?: %s,', $member->getName(), $param);
                }
            } else {
                $body[] = sprintf('  %s: %s,', $member->getName(), $param);
            }
        }

        return $body;
    }

    public static function getFilterConstantFromType(string $type): ?string
    {
        switch ($type) {
            case 'integer':
                return 'FILTER_VALIDATE_INT';
            case 'boolean':
                return 'FILTER_VALIDATE_BOOLEAN';
            case 'string':
            default:
                return null;
        }
    }

    public static function parseDocumentation(string $documentation, bool $singleLine = false): string
    {
        $s = \strtr($documentation, ['> <' => '><']);
        $s = explode("\n", trim(\strtr($s, [
            '<p>' => '',
            '</p>' => "\n",
        ])))[0];

        $s = \strtr($s, [
            '<code>' => '`',
            '</code>' => '`',
            '<i>' => '*',
            '</i>' => '*',
            '<b>' => '**',
            '</b>' => '**',
        ]);

        \preg_match_all('/<a href="([^"]*)">/', $s, $matches);
        $s = \preg_replace('/<a href="[^"]*">([^<]*)<\/a>/', '$1', $s);

        $s = \strtr($s, [
            '<a>' => '',
            '</a>' => '',
        ]);

        if (false !== \strpos($s, '<')) {
            throw new \InvalidArgumentException('remaining HTML code in documentation: ' . $s);
        }

        if (!$singleLine) {
            $s = wordwrap($s, 117);
            $s .= "\n";
            foreach ($matches[1] as $link) {
                $s .= "\n@see $link";
            }
        }

        return $s;
    }
}
