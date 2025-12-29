<?php

namespace AsyncAws\S3Vectors\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListVectorBucketsInput extends Input
{
    /**
     * The maximum number of vector buckets to be returned in the response.
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
     * Limits the response to vector buckets that begin with the specified prefix.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * @param array{
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   prefix?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->maxResults = $input['maxResults'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->prefix = $input['prefix'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   maxResults?: int|null,
     *   nextToken?: string|null,
     *   prefix?: string|null,
     *   '@region'?: string|null,
     * }|ListVectorBucketsInput $input
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
        $uriString = '/ListVectorBuckets';

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

    private function requestBody(): array
    {
        $payload = [];
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
