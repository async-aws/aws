<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

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
    public static function parseDocumentation(string $documentation): string
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

        $s = wordwrap($s, 117);
        $s .= "\n";
        foreach ($matches[1] as $link) {
            $s .= "\n@see $link";
        }

        return $s;
    }
}
