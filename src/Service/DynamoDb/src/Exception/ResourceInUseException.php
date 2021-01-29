<?php

namespace AsyncAws\DynamoDb\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The operation conflicts with the resource's availability. For example, you attempted to recreate an existing table,
 * or tried to delete a table currently in the `CREATING` state.
 */
final class ResourceInUseException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
