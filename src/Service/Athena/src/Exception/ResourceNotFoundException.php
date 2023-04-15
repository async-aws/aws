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
     */
    private $resourceName;

    public function getResourceName(): ?string
    {
        return $this->resourceName;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        if (null !== $v = (isset($data['message']) ? (string) $data['message'] : null)) {
            $this->message = $v;
        }
        $this->resourceName = isset($data['ResourceName']) ? (string) $data['ResourceName'] : null;
    }
}
