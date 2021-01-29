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
    public static function normalizeName(string $propertyName): string
    {
        static $cache;
        if (isset($cache[$propertyName])) {
            return $cache[$propertyName];
        }

        // Ordered by search length to avoid collision and wrong substitution
        static $replacements = [
            'BOOL' => 'Bool',
            'CORS' => 'Cors',
            'ETag' => 'Etag',
            'NULL' => 'Null',
            'AWS' => 'Aws',
            'ACL' => 'Acl',
            'ACP' => 'Acp',
            'EC2' => 'Ec2',
            'KMS' => 'Kms',
            'ARN' => 'Arn',
            'MFA' => 'Mfa',
            'SSE' => 'Sse',
            'SMS' => 'Sms',
            'URI' => 'Uri',
            'MD5' => 'Md5',
            'BS' => 'Bs',
            'ID' => 'Id',
            'NS' => 'Ns',
            'SS' => 'Ss',
        ];
        static $ignored = [
            'GB' => 'Gb',
        ];

        $originalPropertyName = $propertyName;
        $propertyName = \strtr($propertyName, $replacements);

        if (\preg_match('/[A-Z]{2,}/', $propertyName)) {
            $propertyName = \strtr($propertyName, $ignored);
            if (\preg_match('/[A-Z]{2,}/', $propertyName)) {
                throw new \RuntimeException(sprintf('No camel case property "%s" is not yet implemented', $originalPropertyName));
            }
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
