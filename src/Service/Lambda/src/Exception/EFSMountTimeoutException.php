<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The function was able to make a network connection to the configured file system, but the mount operation timed out.
 */
final class EFSMountTimeoutException extends ClientException
{
    private $type;

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->type = isset($data['Type']) ? (string) $data['Type'] : null;
        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
    }
}
