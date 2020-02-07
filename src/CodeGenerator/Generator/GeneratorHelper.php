<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Definition\Shape;

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
            case 'boolean':
                return 'bool';
            case 'integer':
                return 'int';
            case 'timestamp':
                return '\DateTimeImmutable';
            case 'string':
            case 'long':
            case 'blob':
                return 'string';
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

    public static function addMethodComment(ServiceDefinition $definition, Shape $inputShape, string $baseNamespace, bool $allNullable = false): array
    {
        $body = [];
        foreach ($inputShape['members'] as $name => $data) {
            $nullable = !\in_array($name, $inputShape['required'] ?? []);
            $memberShape = $definition->getShape($data['shape']);
            $param = $memberShape['type'];
            if ('structure' === $param) {
                $param = '\\' . $baseNamespace . '\\' . $data['shape'] . '|array';
            } elseif ('list' === $param) {
                $listItemShapeName = $definition->getShape($data['shape'])['member']['shape'];

                // is the list item an object?
                $type = $definition->getShape($listItemShapeName)['type'];
                if ('structure' === $type) {
                    $param = '\\' . $baseNamespace . '\\' . $listItemShapeName . '[]';
                } else {
                    $param = self::toPhpType($type) . '[]';
                }
            } elseif ($data['streaming'] ?? false) {
                $param = 'string|resource|\Closure';
            } elseif ('timestamp' === $param) {
                $param = '\DateTimeInterface|string';
            } else {
                $param = self::toPhpType($param);
            }

            $body[] = sprintf('  %s%s: %s,', $name, $allNullable || $nullable ? '?' : '', $param);
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
