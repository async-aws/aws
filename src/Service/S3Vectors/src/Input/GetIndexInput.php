<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetIndexInput extends Input
{
    /**
     * The name of the vector bucket that contains the vector index.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The name of the vector index.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The ARN of the vector index.
     *
     * @var string|null
     */
    private $indexArn;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->indexName = $input['indexName'] ?? null;
        $this->indexArn = $input['indexArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   '@region'?: string|null,
     * }|GetIndexInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexArn(): ?string
    {
        return $this->indexArn;
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getVectorBucketName(): ?string
    {
        return $this->vectorBucketName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/GetIndex';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setIndexArn(?string $value): self
    {
        $this->indexArn = $value;

        return $this;
    }

    public function setIndexName(?string $value): self
    {
        $this->indexName = $value;

        return $this;
    }

    public function setVectorBucketName(?string $value): self
    {
        $this->vectorBucketName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->vectorBucketName) {
            $payload['vectorBucketName'] = $v;
        }
        if (null !== $v = $this->indexName) {
            $payload['indexName'] = $v;
        }
        if (null !== $v = $this->indexArn) {
            $payload['indexArn'] = $v;
        }

        return $payload;
    }
}
