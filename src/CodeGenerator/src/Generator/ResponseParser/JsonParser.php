<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\Shape;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class JsonParser extends RestJsonParser
{
    protected function parseResponseTimestamp(Shape $shape, string $input, bool $required): string
    {
        $body = '\DateTimeImmutable::createFromFormat(\'U.u\', \sprintf(\'%.6F\', INPUT))';

        if (!$required) {
            $body = 'isset(INPUT) ? ' . $body . ' : null';
        }

        return strtr($body, ['INPUT' => $input]);
    }
}
