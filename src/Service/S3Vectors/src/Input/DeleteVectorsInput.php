<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DeleteVectorsInput extends Input
{
    /**
     * The name of the vector bucket that contains the vector index.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The name of the vector index that contains a vector you want to delete.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The ARN of the vector index that contains a vector you want to delete.
     *
     * @var string|null
     */
    private $indexArn;

    /**
     * The keys of the vectors to delete.
     *
     * @required
     *
     * @var string[]|null
     */
    private $keys;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   keys?: string[],
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->indexName = $input['indexName'] ?? null;
        $this->indexArn = $input['indexArn'] ?? null;
        $this->keys = $input['keys'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   indexName?: string|null,
     *   indexArn?: string|null,
     *   keys?: string[],
     *   '@region'?: string|null,
     * }|DeleteVectorsInput $input
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

    /**
     * @return string[]
     */
    public function getKeys(): array
    {
        return $this->keys ?? [];
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
        $uriString = '/DeleteVectors';

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

    /**
     * @param string[] $value
     */
    public function setKeys(array $value): self
    {
        $this->keys = $value;

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
        if (null === $v = $this->keys) {
            throw new InvalidArgument(\sprintf('Missing parameter "keys" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['keys'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['keys'][$index] = $listValue;
        }

        return $payload;
    }
}
