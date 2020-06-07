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
    public function getHeaders(Operation $operation): string
    {
        return strtr(<<<PHP
[
    'Content-Type' => 'application/x-amz-json-VERSION',
    'X-Amz-Target' => 'TARGET',
];

PHP
        , [
            'VERSION' => number_format($operation->getService()->getJsonVersion(), 1),
            'TARGET' => sprintf('%s.%s', $operation->getService()->getTargetPrefix(), $operation->getName()),
        ]);
    }
}
