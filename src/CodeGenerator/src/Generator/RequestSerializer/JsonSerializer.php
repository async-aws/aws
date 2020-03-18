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
class JsonSerializer extends RestJsonSerializer
{
    public function getHeaders(Operation $operation): string
    {
        return strtr(<<<PHP
[
    'Content-Type' => 'application/x-amz-json-1.0',
    'X-Amz-Target' => TARGET,
];

PHP,
        [
            'TARGET' => sprintf('"%s.%s"', $operation->getService()->getTargetPrefix(), $operation->getName()),
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
