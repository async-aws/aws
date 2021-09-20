<?php

namespace AsyncAws\AppSync\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The API key expiration must be set to a value between 1 and 365 days from creation (for `CreateApiKey`) or from
 * update (for `UpdateApiKey`).
 */
final class ApiKeyValidityOutOfBoundsException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
