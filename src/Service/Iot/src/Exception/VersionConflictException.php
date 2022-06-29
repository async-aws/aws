<?php

namespace AsyncAws\Iot\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * An exception thrown when the version of an entity specified with the `expectedVersion` parameter does not match the
 * latest version in the system.
 */
final class VersionConflictException extends ClientException
{
    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
