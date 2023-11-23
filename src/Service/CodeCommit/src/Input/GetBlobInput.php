<?php

namespace AsyncAws\CodeCommit\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a get blob operation.
 */
final class GetBlobInput extends Input
{
    /**
     * The name of the repository that contains the blob.
     *
     * @required
     *
     * @var string|null
     */
    private $repositoryName;

    /**
     * The ID of the blob, which is its SHA-1 pointer.
     *
     * @required
     *
     * @var string|null
     */
    private $blobId;

    /**
     * @param array{
     *   repositoryName?: string,
     *   blobId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->repositoryName = $input['repositoryName'] ?? null;
        $this->blobId = $input['blobId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   repositoryName?: string,
     *   blobId?: string,
     *   '@region'?: string|null,
     * }|GetBlobInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBlobId(): ?string
    {
        return $this->blobId;
    }

    public function getRepositoryName(): ?string
    {
        return $this->repositoryName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'Accept' => 'application/json',
            'X-Amz-Target' => 'CodeCommit_20150413.GetBlob',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBlobId(?string $value): self
    {
        $this->blobId = $value;

        return $this;
    }

    public function setRepositoryName(?string $value): self
    {
        $this->repositoryName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->repositoryName) {
            throw new InvalidArgument(sprintf('Missing parameter "repositoryName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['repositoryName'] = $v;
        if (null === $v = $this->blobId) {
            throw new InvalidArgument(sprintf('Missing parameter "blobId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['blobId'] = $v;

        return $payload;
    }
}
