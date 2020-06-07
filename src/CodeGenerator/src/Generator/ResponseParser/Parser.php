<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\ResponseParser;

use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * This will generate code to parse a HTTP response body into a Result.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface Parser
{
    public function generate(StructureShape $shape): ParserResult;
}
