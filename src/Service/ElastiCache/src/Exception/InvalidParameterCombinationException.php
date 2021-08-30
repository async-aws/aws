<?php

namespace AsyncAws\ElastiCache\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Two or more incompatible parameters were specified.
 */
final class InvalidParameterCombinationException extends ClientException
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
