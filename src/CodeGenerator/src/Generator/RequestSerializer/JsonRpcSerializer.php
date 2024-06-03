<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\Operation;

/**
 * Serialize to AWS json.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 *
 * @internal
 */
class JsonRpcSerializer extends RestJsonSerializer
{
    public function getHeaders(Operation $operation, bool $withPayload): string
    {
        if (!$withPayload) {
            return "['Accept' => 'application/json']";
        }

        return strtr("[
            'Content-Type' => 'application/x-amz-json-VERSION',
            'X-Amz-Target' => 'TARGET',
            'Accept' => 'application/json',
        ]", [
            'VERSION' => number_format($operation->getService()->getJsonVersion(), 1),
            'TARGET' => sprintf('%s.%s', $operation->getService()->getTargetPrefix(), $operation->getName()),
        ]);
    }
}
