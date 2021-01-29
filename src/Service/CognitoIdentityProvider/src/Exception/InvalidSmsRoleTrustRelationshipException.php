<?php

namespace AsyncAws\CognitoIdentityProvider\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * This exception is thrown when the trust relationship is invalid for the role provided for SMS configuration. This can
 * happen if you do not trust **cognito-idp.amazonaws.com** or the external ID provided in the role does not match what
 * is provided in the SMS configuration for the user pool.
 */
final class InvalidSmsRoleTrustRelationshipException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
