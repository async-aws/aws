<?php

namespace AsyncAws\LocationService\Exception;

use AsyncAws\Core\Exception\Http\ServerException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request has failed to process because of an unknown server error, exception, or failure.
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
