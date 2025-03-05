<?php

namespace AsyncAws\BedrockRuntime\Exception;

use AsyncAws\Core\Exception\Http\ClientException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * The request failed due to an error while processing the model.
 */
final class ModelErrorException extends ClientException
{
    /**
     * The original status code.
     *
     * @var int|null
     */
    private $originalStatusCode;

    /**
     * The resource name.
     *
     * @var string|null
     */
    private $resourceName;

    public function getOriginalStatusCode(): ?int
    {
        return $this->originalStatusCode;
    }

    public function getResourceName(): ?string
    {
        return $this->resourceName;
    }

    protected function populateResult(ResponseInterface $response): void
    {
        $data = $response->toArray(false);

        $this->originalStatusCode = isset($data['originalStatusCode']) ? (int) $data['originalStatusCode'] : null;
        $this->resourceName = isset($data['resourceName']) ? (string) $data['resourceName'] : null;
    }
}
