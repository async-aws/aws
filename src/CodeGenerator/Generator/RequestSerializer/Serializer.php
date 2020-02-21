<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\StructureMember;
use AsyncAws\CodeGenerator\Definition\StructureShape;

/**
 * This will generate code to serialize request Input to a string. Ie to create request body.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
interface Serializer
{
    public function generateForMember(StructureMember $member, string $payloadProperty): string;

    public function generateForShape(Operation $operation, StructureShape $shape): string;

    public function getContentType(): string;
}
