<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\Shape;

/**
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class JsonRpcParser extends RestJsonParser
{
    protected function parseResponseTimestamp(Shape $shape, string $input, bool $required): string
    {
        if ($required) {
            $body = '/** @var \DateTimeImmutable $d */ $d = \DateTimeImmutable::createFromFormat(\'U.u\', \sprintf(\'%.6F\', INPUT))';
        } else {
            $body = '(isset(INPUT) && ($d = \DateTimeImmutable::createFromFormat(\'U.u\', \sprintf(\'%.6F\', INPUT)))) ? $d : null';
        }

        return strtr($body, ['INPUT' => $input]);
    }
}
