<?php

namespace AsyncAws\Athena\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * A resource, such as a workgroup, was not found.
 */
final class ResourceNotFoundException extends ClientException
{
    /**
     * The name of the Amazon resource.
     *
     * @var string|null
     */
    private $resourceName;

    public function getResourceName(): ?string
    {
        return $this->resourceName;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->resourceName = isset($data['ResourceName']) ? (string) $data['ResourceName'] : null;
    }
}
