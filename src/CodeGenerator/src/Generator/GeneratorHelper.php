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
    public static function sanitizePropertyName(string $propertyName): string
    {
        static $cache;
        if (isset($cache[$propertyName])) {
            return $cache[$propertyName];
        }

        static $replacements = [
            'ACL' => 'acl',
            'ARN' => 'arn',
            'BOOL' => 'bool',
            'BS' => 'bs',
            'ID' => 'id',
            'MFA' => 'mfa',
            'SS' => 'ss',
            'NULL' => 'null',
            'NS' => 'ns',
            'URI' => 'uri',
            'ETag' => 'etag',
        ];
        static $replacements3 = [
            'MD5' => 'md5',
            'MFA' => 'mfa',
            'KMS' => 'kms',
            'SMS' => 'sms',
            'SSE' => 'sse',
        ];
        if (isset($replacements[$propertyName])) {
            return $cache[$propertyName] = $replacements[$propertyName];
        }
        if (isset($replacements3[$sub3 = substr($propertyName, 0, 3)])) {
            $sanitized = \preg_replace('/^SSEKMS/', 'sseKms', $propertyName);

            return $cache[$propertyName] = $replacements3[$sub3] . substr($sanitized, 3);
        }
        if (\preg_match('/^[A-Z]{2,}/', $propertyName)) {
            throw new \RuntimeException(sprintf('No camel case property "%s" is not yet implemented', $propertyName));
        }

        return $cache[$propertyName] = \lcfirst($propertyName);
    }

    public static function parseDocumentation(string $documentation, bool $short = true): string
    {
        $s = preg_replace('/>\s*</', '><', $documentation);
        $s = trim(\strtr($s, [
            '<p>' => '',
            '<p/>' => '',
            '</p>' => "\n",
        ]));
        if ($short) {
            $s = explode("\n", $s)[0];
        }

        $s = \strtr($s, [
            '<code>' => '`',
            '</code>' => '`',
            '<i>' => '*',
            '</i>' => '*',
            '<b>' => '**',
            '</b>' => '**',
        ]);
        $s = preg_replace('/\n*<(\/?(note|important|ul|li))>\n*/', "\n<\$1>\n", $s);
        $s = preg_replace('/\n+/', "\n", $s);

        \preg_match_all('/<a href="([^"]*)">/', $s, $matches);
        $s = \preg_replace('/<a href="[^"]*">([^<]*)<\/a>/', '$1', $s);

        $s = \strtr($s, [
            '<a>' => '',
            '</a>' => '',
        ]);

        $prefix = '';
        $lines = [];
        $empty = false;
        $spaceNext = false;

        // converts <li> into `- ` AND handle multi-level list
        foreach (\explode("\n", $s) as $line) {
            $line = trim($line);
            if ('' === $line) {
                $empty = true;
                $lines[] = $prefix;

                continue;
            }

            if ('<li>' === $line) {
                $prefix .= '- ';
                $spaceNext = true;

                continue;
            }
            if ('</li>' === $line) {
                $prefix = \substr($prefix, 0, -2);
                $spaceNext = false;

                continue;
            }
            if ('<ul>' === $line) {
                if (!$empty) {
                    $lines[] = $prefix;
                }
                $empty = true;

                continue;
            }
            if ('</ul>' === $line) {
                $lines[] = $prefix;
                $empty = true;

                continue;
            }
            if ('<note>' === $line) {
                if (!$empty) {
                    $lines[] = $prefix;
                }
                $empty = true;
                $prefix .= '> ';

                continue;
            }
            if ('<important>' === $line) {
                if (!$empty) {
                    $lines[] = $prefix;
                }
                $empty = true;
                $prefix .= '! ';

                continue;
            }
            if ('</note>' === $line || '</important>' === $line) {
                $prefix = \substr($prefix, 0, -2);
                $lines[] = $prefix;
                $empty = true;

                continue;
            }

            $empty = false;
            foreach (explode("\n", wordwrap(trim($line), 117 - \strlen($prefix))) as $l) {
                $lines[] = $prefix . $l;
                if ($spaceNext) {
                    $prefix = \substr($prefix, 0, -2) . '  ';
                    $spaceNext = false;
                }
            }
        }
        $s = \implode("\n", $lines);

        if (false !== \strpos($s, '<')) {
            throw new \InvalidArgumentException('remaining HTML code in documentation: ' . $s);
        }

        $s = \implode("\n", $lines);
        $s .= "\n";
        foreach ($matches[1] as $link) {
            $s .= "\n@see $link";
        }

        return $s;
    }
}
