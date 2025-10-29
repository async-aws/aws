<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The processed request payload exceeded the `Invoke` request body size limit for asynchronous invocations. While the
 * event payload may be under 1 MB, the size after internal serialization exceeds the maximum allowed size for
 * asynchronous invocations.
 */
final class SerializedRequestEntityTooLargeException extends ClientException
{
    /**
     * The error type.
     *
     * @var string|null
     */
    private $type;

    public function getType(): ?string
    {
        return $this->type;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->type = isset($data['Type']) ? (string) $data['Type'] : null;
    }
}
