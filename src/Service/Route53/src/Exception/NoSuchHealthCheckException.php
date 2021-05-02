<?php

namespace AsyncAws\Route53\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * No health check exists with the specified ID.
 */
final class NoSuchHealthCheckException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        if (0 < $data->Error->count()) {
            $data = $data->Error;
        }
        if (null !== $v = (($v = $data->message) ? (string) $v : null)) {
            $this->message = $v;
        }
    }
}
