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
    public function getHeaders(Operation $operation, bool $requestPayload, bool $responsePayload): string
    {
        $headers = [];
        if ($requestPayload) {
            $headers[] = \sprintf(
                "'Content-Type' => 'application/x-amz-json-%s'",
                number_format($operation->getService()->getJsonVersion(), 1)
            );
            $headers[] = \sprintf(
                "'X-Amz-Target' => '%s.%s'",
                $operation->getService()->getTargetPrefix(),
                $operation->getName()
            );
        }
        if ($responsePayload) {
            $headers[] = "'Accept' => 'application/json'";
        }

        switch (count($headers)) {
            case 0:
                return '[]';
            case 1:
                return '['.$headers[0].']';
            default:
                return '[
                    '.implode(",\n", $headers).'
                ]';
        }
    }
}
