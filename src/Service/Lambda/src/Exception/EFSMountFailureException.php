<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The function couldn't mount the configured file system due to a permission or configuration issue.
 */
final class EFSMountFailureException extends ClientException
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
