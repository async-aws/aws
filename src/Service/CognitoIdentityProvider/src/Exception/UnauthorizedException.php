<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Exception that is thrown when the request isn't authorized. This can happen due to an invalid access token in the
 * request.
 */
final class UnauthorizedException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
