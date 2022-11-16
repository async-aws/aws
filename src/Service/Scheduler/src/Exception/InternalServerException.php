<?php

namespace AsyncAws\Scheduler\Exception;

use AsyncAws\Core\Exception\Http\ServerException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Unexpected error encountered while processing the request.
 */
final class InternalServerException extends ServerException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
