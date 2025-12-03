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
            'FourCC' => 'Fourcc',
            'SHA256' => 'Sha256',
            '3DLUT' => '3Dlut',
            'CRC32' => 'Crc32',
            'CRC64' => 'Crc64',
            'BOOL' => 'Bool',
            'CORS' => 'Cors',
            'ECS' => 'Ecs',
            'ETag' => 'Etag',
            'NULL' => 'Null',
            'NVME' => 'Nvme',
            'SHA1' => 'Sha1',
            'AWS' => 'Aws',
            'ACL' => 'Acl',
            'ACP' => 'Acp',
            'EC2' => 'Ec2',
            'KMS' => 'Kms',
            'ARN' => 'Arn',
            'VPC' => 'Vpc',
            'STL' => 'Stl',
            'TTL' => 'Ttl',
            'DNS' => 'Dns',
            'MFA' => 'Mfa',
            'PTS' => 'Pts',
            'SSE' => 'Sse',
            'SMS' => 'Sms',
            'RGB' => 'Rgb',
            'YUV' => 'Yuv',
            'URI' => 'Uri',
            'MD5' => 'Md5',
            'BS' => 'Bs',
            'ID' => 'Id',
            'NS' => 'Ns',
            'SS' => 'Ss',
        ];
        static $ignored = [
            'GB' => 'Gb',
            'GiB' => 'Gib',
            'BFrame' => 'Bframe',
            'BReference' => 'Breference',
            'BBox' => 'Bbox',
            'VCpu' => 'Vcpu',
            'IInterval' => 'Iinterval',
            'IFrame' => 'Iframe',
            'XCoordinate' => 'Xcoordinate',
            'YCoordinate' => 'Ycoordinate',
            'XOffset' => 'Xoffset',
            'YOffset' => 'Yoffset',
            'XPosition' => 'Xposition',
            'YPosition' => 'Yposition',
        ];

        $originalPropertyName = $propertyName;
        $propertyName = strtr($propertyName, $replacements);

        if (preg_match('/[A-Z]{2,}/', $propertyName)) {
            $propertyName = strtr($propertyName, $ignored);
            if (preg_match('/[A-Z]{2,}/', $propertyName)) {
                throw new \RuntimeException(\sprintf('No camel case property "%s" is not yet implemented', $originalPropertyName));
            }
        }

        return $cache[$propertyName] = lcfirst($propertyName);
    }

    public static function parseDocumentation(string $documentation): string
    {
        $s = preg_replace('/>\s*</', '><', $documentation);
        $s = trim(strtr($s, [
            '<p class="title">' => '<p>',
            '<p/>' => '',
            '</p>' => '',
            '<u>' => '',
            '</u>' => '',
            '<dl>' => '<ul>',
            '</dl>' => '</ul>',
        ]));

        $s = strtr($s, [
            '<pre><code>' => '```' . "\n",
            '</code></pre>' => "\n" . '```',
            '<code>' => '`',
            '</code>' => '`',
            '<i>' => '*',
            '</i>' => '*',
            '<b>' => '**',
            '</b>' => '**',
            '<dt>' => '<li>`',
            '</dt><dd>' => '`: ',
            '<dd>' => '<li>',
            '</dd>' => '</li>',
            '</dt>' => '`</li>',
        ]);
        $s = preg_replace('/\n*<(\/?(note|important|ul|ol|li|dd|dt|p))>\n*/', "\n<\$1>\n", $s);
        $s = preg_replace('/\n+/', "\n", $s);

        preg_match_all('/<a href="([^"]*)">/', $s, $matches);
        $footnotes = [];
        $s = preg_replace_callback('/<a href="([^"]*)">([^<]*)<\/a>/', static function ($match) use (&$footnotes) {
            $footnotes[] = $match[1];
            $counter = \count($footnotes);

            return "$match[2] [^$counter]";
        }, $s);
        $s = preg_replace('/<(Role|Accessibility)[^>]*+>/', '`$0`', $s);

        $s = strtr($s, [
            '<a>' => '',
            '</a>' => '',
        ]);

        $s = trim($s);

        $prefix = [];
        $lines = [];
        $empty = false;
        $spaceNext = false;

        // converts <li>/ol into `- `/`1.` AND handle multi-level list
        $counters = [];
        $counter = null;
        foreach (explode("\n", $s) as $line) {
            $line = trim($line);
            if ('' === $line) {
                $empty = true;
                $lines[] = implode('', $prefix);

                continue;
            }

            if ('<p>' === $line) {
                if ($spaceNext) {
                    // This is a <p> inside a <li> Lets ignore it
                    continue;
                }

                if (!$empty) {
                    $lines[] = implode('', $prefix);
                }
                $empty = true;

                continue;
            }

            if ('<li>' === $line) {
                if (null === $counter) {
                    $prefix[] = '- ';
                } else {
                    ++$counter;
                    $prefix[] = $counter . '. ';
                }
                $spaceNext = true;

                continue;
            }
            if ('</li>' === $line) {
                array_pop($prefix);
                $spaceNext = false;

                continue;
            }
            if ('<ul>' === $line) {
                if (!$empty) {
                    $lines[] = implode('', $prefix);
                }
                $empty = true;
                $counters[] = null;
                $counter = &$counters[\count($counters) - 1];

                continue;
            }
            if ('</ul>' === $line || '</ol>' === $line) {
                $lines[] = implode('', $prefix);
                $empty = true;
                array_pop($counters);
                $counter = &$counters[\count($counters) - 1];

                continue;
            }
            if ('<ol>' === $line) {
                if (!$empty) {
                    $lines[] = implode('', $prefix);
                }
                $empty = true;
                $counters[] = 0;
                $counter = &$counters[\count($counters) - 1];

                continue;
            }
            if ('<note>' === $line) {
                if (!$empty) {
                    $lines[] = implode('', $prefix);
                }
                $empty = true;
                $prefix[] = '> ';

                continue;
            }
            if ('<important>' === $line) {
                if (!$empty) {
                    $lines[] = implode('', $prefix);
                }
                $empty = true;
                $prefix[] = '! ';

                continue;
            }
            if ('</note>' === $line || '</important>' === $line) {
                array_pop($prefix);
                $lines[] = implode('', $prefix);
                $empty = true;

                continue;
            }

            $empty = false;
            foreach (explode("\n", wordwrap(trim(str_replace('  ', ' ', $line)), 117 - \strlen(implode('', $prefix)))) as $l) {
                $lines[] = implode('', $prefix) . $l;
                if ($spaceNext) {
                    $last = array_pop($prefix);
                    $prefix[] = str_repeat(' ', \strlen($last));
                    $spaceNext = false;
                }
            }
        }
        $s = implode("\n", $lines);
        $s = preg_replace('/`[^`]*`/', '', $s); // Remove the code blocks before checking for remaining HTML

        if (false !== strpos($s, '</') || false !== strpos($s, '/>')) {
            throw new \InvalidArgumentException('remaining HTML code in documentation: ' . $s);
        }

        $s = implode("\n", $lines);
        $s = html_entity_decode($s);
        $s = trim($s);
        $s .= "\n";
        $counter = 0;
        foreach ($footnotes as $footnote) {
            ++$counter;
            $s .= "\n[^$counter]: $footnote";
        }

        return $s;
    }
}
