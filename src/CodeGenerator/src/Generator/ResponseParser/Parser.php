<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * This will generate code to parse a HTTP response body into a Result.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
interface Parser
{
    public function generate(StructureShape $shape, bool $throwOnError = true): ParserResult;

    public function generateForPath(StructureShape $shape, string $path, string $output): ParserResult;
}
