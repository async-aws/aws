<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * This will generate code to serialize request Input to a string. Ie to create request body.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface Serializer
{
    public function generateRequestBody(Operation $operation, StructureShape $shape): array;

    public function generateRequestBuilder(StructureShape $shape): array;

    public function getContentType(): string;
}
