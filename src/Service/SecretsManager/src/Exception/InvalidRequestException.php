<?php

namespace AsyncAws\SecretsManager\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * You provided a parameter value that is not valid for the current state of the resource.
 * Possible causes:.
 *
 * - You tried to perform the operation on a secret that's currently marked deleted.
 * - You tried to enable rotation on a secret that doesn't already have a Lambda function ARN configured and you didn't
 *   include such an ARN as a parameter in this call.
 */
final class InvalidRequestException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
