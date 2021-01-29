<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * This exception is thrown when there is a code mismatch and the service fails to configure the software token TOTP
 * multi-factor authentication (MFA).
 */
final class EnableSoftwareTokenMFAException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
