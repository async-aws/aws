<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `ListStreams`.
 */
final class ListStreamsInput extends Input
{
    /**
     * The maximum number of streams to list. The default value is 100. If you specify a value greater than 100, at most 100
     * results are returned.
     *
     * @var int|null
     */
    private $limit;

    /**
     * The name of the stream to start the list with.
     *
     * @var string|null
     */
    private $exclusiveStartStreamName;

    /**
     * @var string|null
     */
    private $nextToken;

    /**
     * @param array{
     *   Limit?: int,
     *   ExclusiveStartStreamName?: string,
     *   NextToken?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->limit = $input['Limit'] ?? null;
        $this->exclusiveStartStreamName = $input['ExclusiveStartStreamName'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExclusiveStartStreamName(): ?string
    {
        return $this->exclusiveStartStreamName;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.ListStreams',
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

    public function setExclusiveStartStreamName(?string $value): self
    {
        $this->exclusiveStartStreamName = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->exclusiveStartStreamName) {
            $payload['ExclusiveStartStreamName'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }

        return $payload;
    }
}
