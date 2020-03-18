<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator\RequestSerializer;

use AsyncAws\CodeGenerator\Definition\Operation;
use AsyncAws\CodeGenerator\Definition\Shape;

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

PHP,
        [
            'VERSION' => number_format($operation->getService()->getJsonVersion(), 1),
            'TARGET' => sprintf('%s.%s', $operation->getService()->getTargetPrefix(), $operation->getName()),
        ]);
    }

    protected function dumpArrayBoolean(string $output, string $input, Shape $shape): string
    {
        return strtr('$payloadOUTPUT = (bool) INPUT;', [
            'OUTPUT' => $output,
            'INPUT' => $input,
        ]);
    }
}
