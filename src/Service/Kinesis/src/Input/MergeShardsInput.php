<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `MergeShards`.
 */
final class MergeShardsInput extends Input
{
    /**
     * The name of the stream for the merge.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The shard ID of the shard to combine with the adjacent shard for the merge.
     *
     * @required
     *
     * @var string|null
     */
    private $shardToMerge;

    /**
     * The shard ID of the adjacent shard for the merge.
     *
     * @required
     *
     * @var string|null
     */
    private $adjacentShardToMerge;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: string,
     *   ShardToMerge?: string,
     *   AdjacentShardToMerge?: string,
     *   StreamARN?: string,
     *
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->shardToMerge = $input['ShardToMerge'] ?? null;
        $this->adjacentShardToMerge = $input['AdjacentShardToMerge'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdjacentShardToMerge(): ?string
    {
        return $this->adjacentShardToMerge;
    }

    public function getShardToMerge(): ?string
    {
        return $this->shardToMerge;
    }

    public function getStreamArn(): ?string
    {
        return $this->streamArn;
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
            'X-Amz-Target' => 'Kinesis_20131202.MergeShards',
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

    public function setAdjacentShardToMerge(?string $value): self
    {
        $this->adjacentShardToMerge = $value;

        return $this;
    }

    public function setShardToMerge(?string $value): self
    {
        $this->shardToMerge = $value;

        return $this;
    }

    public function setStreamArn(?string $value): self
    {
        $this->streamArn = $value;

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
        if (null === $v = $this->shardToMerge) {
            throw new InvalidArgument(sprintf('Missing parameter "ShardToMerge" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ShardToMerge'] = $v;
        if (null === $v = $this->adjacentShardToMerge) {
            throw new InvalidArgument(sprintf('Missing parameter "AdjacentShardToMerge" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AdjacentShardToMerge'] = $v;
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
