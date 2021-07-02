<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request failed because it would exceed one of the Secrets Manager internal limits.
 */
final class LimitExceededException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
