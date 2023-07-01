<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * This will generate code to serialize request Input to a string. Ie to create request body.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
interface Serializer
{
    /**
     * Returns the body of the method, a boolean indicating whether a requestBody method is needed
     * and optionally a list of extra parameters for the method with their type.
     *
     * @return array{0: string, 1: bool, 2?: array<string, string>}
     */
    public function generateRequestBody(Operation $operation, StructureShape $shape): array;

    /**
     * Returns the return type, the body and extra arguments for the requestBody method.
     *
     * @return array{0: string, 1: string, 2?: array<string, string>}
     */
    public function generateRequestBuilder(StructureShape $shape): array;

    public function getHeaders(Operation $operation): string;
}
