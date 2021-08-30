<?php

namespace AsyncAws\ElastiCache\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The value for a parameter is invalid.
 */
final class InvalidParameterValueException extends ClientException
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
