<?php

namespace AsyncAws\TimestreamWrite\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * You are not authorized to perform this action.
 */
final class AccessDeniedException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->message = (string) $data['message'];
    }
}
