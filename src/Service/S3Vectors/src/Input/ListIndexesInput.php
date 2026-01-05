<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListIndexesInput extends Input
{
    /**
     * The name of the vector bucket that contains the vector indexes.
     *
     * @var string|null
     */
    private $vectorBucketName;

    /**
     * The ARN of the vector bucket that contains the vector indexes.
     *
     * @var string|null
     */
    private $vectorBucketArn;

    /**
     * The maximum number of items to be returned in the response.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * The previous pagination token.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Limits the response to vector indexes that begin with the specified prefix.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   prefix?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->vectorBucketName = $input['vectorBucketName'] ?? null;
        $this->vectorBucketArn = $input['vectorBucketArn'] ?? null;
        $this->maxResults = $input['maxResults'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->prefix = $input['prefix'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   vectorBucketName?: string|null,
     *   vectorBucketArn?: string|null,
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   prefix?: string|null,
     *   '@region'?: string|null,
     * }|ListIndexesInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function getVectorBucketArn(): ?string
    {
        return $this->vectorBucketArn;
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
        $uriString = '/ListIndexes';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setPrefix(?string $value): self
    {
        $this->prefix = $value;

        return $this;
    }

    public function setVectorBucketArn(?string $value): self
    {
        $this->vectorBucketArn = $value;

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
        if (null !== $v = $this->vectorBucketArn) {
            $payload['vectorBucketArn'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['maxResults'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }
        if (null !== $v = $this->prefix) {
            $payload['prefix'] = $v;
        }

        return $payload;
    }
}
