<?php

namespace AsyncAws\Iot\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The resource already exists.
 */
final class ResourceAlreadyExistsException extends ClientException
{
    /**
     * The ID of the resource that caused the exception.
     *
     * @var string|null
     */
    private $resourceId;

    /**
     * The ARN of the resource that caused the exception.
     *
     * @var string|null
     */
    private $resourceArn;

    public function getResourceArn(): ?string
    {
        return $this->resourceArn;
    }

    public function getResourceId(): ?string
    {
        return $this->resourceId;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->resourceId = isset($data['resourceId']) ? (string) $data['resourceId'] : null;
        $this->resourceArn = isset($data['resourceArn']) ? (string) $data['resourceArn'] : null;
    }
}
