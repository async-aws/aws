<?php

namespace AsyncAws\Core\Sts\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request could not be fulfilled because the identity provider (IDP) that was asked to verify the incoming identity
 * token could not be reached. This is often a transient error caused by network conditions. Retry the request a limited
 * number of times so that you don't exceed the request rate. If the error persists, the identity provider might be down
 * or not responding.
 */
final class IDPCommunicationErrorException extends ClientException
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
