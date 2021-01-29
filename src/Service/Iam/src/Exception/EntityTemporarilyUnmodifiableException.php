<?php

namespace AsyncAws\Iam\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request was rejected because it referenced an entity that is temporarily unmodifiable, such as a user name that
 * was deleted and then recreated. The error indicates that the request is likely to succeed if you try again after
 * waiting several minutes. The error message describes the entity.
 */
final class EntityTemporarilyUnmodifiableException extends ClientException
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
