<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `DescribeStream`.
 */
final class DescribeStreamInput extends Input
{
    /**
     * The name of the stream to describe.
     *
     * @required
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The maximum number of shards to return in a single call. The default value is 100. If you specify a value greater
     * than 100, at most 100 shards are returned.
     *
     * @var int|null
     */
    private $limit;

    /**
     * The shard ID of the shard to start with.
     *
     * @var string|null
     */
    private $exclusiveStartShardId;

    /**
     * @param array{
     *   StreamName?: string,
     *   Limit?: int,
     *   ExclusiveStartShardId?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->exclusiveStartShardId = $input['ExclusiveStartShardId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getExclusiveStartShardId(): ?string
    {
        return $this->exclusiveStartShardId;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getStreamName(): ?string
    {
        return $this->streamName;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Kinesis_20131202.DescribeStream',
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

    public function setExclusiveStartShardId(?string $value): self
    {
        $this->exclusiveStartShardId = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setStreamName(?string $value): self
    {
        $this->streamName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->streamName) {
            throw new InvalidArgument(sprintf('Missing parameter "StreamName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StreamName'] = $v;
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->exclusiveStartShardId) {
            $payload['ExclusiveStartShardId'] = $v;
        }

        return $payload;
    }
}
