<?php

namespace AsyncAws\Lambda\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The runtime version used by your function is no longer available. Update your function to use an available runtime
 * version or runtime alias.
 */
final class RuntimeVersionDeletedException extends ClientException
{
    /**
     * Indicates the exception type for a deleted runtime version.
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
