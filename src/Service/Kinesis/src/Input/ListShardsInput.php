<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Kinesis\ValueObject\ShardFilter;

final class ListShardsInput extends Input
{
    /**
     * The name of the data stream whose shards you want to list.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * When the number of shards in the data stream is greater than the default value for the `MaxResults` parameter, or if
     * you explicitly specify a value for `MaxResults` that is less than the number of shards in the data stream, the
     * response includes a pagination token named `NextToken`. You can specify this `NextToken` value in a subsequent call
     * to `ListShards` to list the next set of shards.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Specify this parameter to indicate that you want to list the shards starting with the shard whose ID immediately
     * follows `ExclusiveStartShardId`.
     *
     * @var string|null
     */
    private $exclusiveStartShardId;

    /**
     * The maximum number of shards to return in a single call to `ListShards`. The maximum number of shards to return in a
     * single call. The default value is 1000. If you specify a value greater than 1000, at most 1000 results are returned.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * Specify this input parameter to distinguish data streams that have the same name. For example, if you create a data
     * stream and then delete it, and you later create another data stream with the same name, you can use this input
     * parameter to specify which of the two streams you want to list the shards for.
     *
     * @var \DateTimeImmutable|null
     */
    private $streamCreationTimestamp;

    /**
     * Enables you to filter out the response of the `ListShards` API. You can only specify one filter at a time.
     *
     * @var ShardFilter|null
     */
    private $shardFilter;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: string,
     *   NextToken?: string,
     *   ExclusiveStartShardId?: string,
     *   MaxResults?: int,
     *   StreamCreationTimestamp?: \DateTimeImmutable|string,
     *   ShardFilter?: ShardFilter|array,
     *   StreamARN?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->exclusiveStartShardId = $input['ExclusiveStartShardId'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->streamCreationTimestamp = !isset($input['StreamCreationTimestamp']) ? null : ($input['StreamCreationTimestamp'] instanceof \DateTimeImmutable ? $input['StreamCreationTimestamp'] : new \DateTimeImmutable($input['StreamCreationTimestamp']));
        $this->shardFilter = isset($input['ShardFilter']) ? ShardFilter::create($input['ShardFilter']) : null;
        $this->streamArn = $input['StreamARN'] ?? null;
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

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getShardFilter(): ?ShardFilter
    {
        return $this->shardFilter;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
    }

    public function getStreamCreationTimestamp(): ?\DateTimeImmutable
    {
        return $this->streamCreationTimestamp;
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
            'X-Amz-Target' => 'Kinesis_20131202.ListShards',
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

    public function setShardFilter(?ShardFilter $value): self
    {
        $this->shardFilter = $value;

        return $this;
    }

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

        return $this;
    }

    public function setStreamCreationTimestamp(?\DateTimeImmutable $value): self
    {
        $this->streamCreationTimestamp = $value;

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
        if (null !== $v = $this->streamName) {
            $payload['StreamName'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->exclusiveStartShardId) {
            $payload['ExclusiveStartShardId'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }
        if (null !== $v = $this->streamCreationTimestamp) {
            $payload['StreamCreationTimestamp'] = $v->getTimestamp();
        }
        if (null !== $v = $this->shardFilter) {
            $payload['ShardFilter'] = $v->requestBody();
        }
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
