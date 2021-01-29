<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ServerException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The runtime or runtime version specified is not supported.
 */
final class InvalidRuntimeException extends ServerException
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
