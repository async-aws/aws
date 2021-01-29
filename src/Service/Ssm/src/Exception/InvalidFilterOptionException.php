<?php

namespace AsyncAws\Ssm\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The specified filter option is not valid. Valid options are Equals and BeginsWith. For Path filter, valid options are
 * Recursive and OneLevel.
 */
final class InvalidFilterOptionException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
