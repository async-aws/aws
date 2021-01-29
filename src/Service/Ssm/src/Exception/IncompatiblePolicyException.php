<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * There is a conflict in the policies specified for this parameter. You can't, for example, specify two Expiration
 * policies for a parameter. Review your policies, and try again.
 */
final class IncompatiblePolicyException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
