<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The parameter couldn't be found. Verify the name and try again.
 */
final class ParameterNotFoundException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
