<?php

namespace AsyncAws\Kinesis\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input for `SplitShard`.
 */
final class SplitShardInput extends Input
{
    /**
     * The name of the stream for the shard split.
     *
     * @var string|null
     */
    private $streamName;

    /**
     * The shard ID of the shard to split.
     *
     * @required
     *
     * @var string|null
     */
    private $shardToSplit;

    /**
     * A hash key value for the starting hash key of one of the child shards created by the split. The hash key range for a
     * given shard constitutes a set of ordered contiguous positive integers. The value for `NewStartingHashKey` must be in
     * the range of hash keys being mapped into the shard. The `NewStartingHashKey` hash key value and all higher hash key
     * values in hash key range are distributed to one of the child shards. All the lower hash key values in the range are
     * distributed to the other child shard.
     *
     * @required
     *
     * @var string|null
     */
    private $newStartingHashKey;

    /**
     * The ARN of the stream.
     *
     * @var string|null
     */
    private $streamArn;

    /**
     * @param array{
     *   StreamName?: string|null,
     *   ShardToSplit?: string,
     *   NewStartingHashKey?: string,
     *   StreamARN?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->shardToSplit = $input['ShardToSplit'] ?? null;
        $this->newStartingHashKey = $input['NewStartingHashKey'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   StreamName?: string|null,
     *   ShardToSplit?: string,
     *   NewStartingHashKey?: string,
     *   StreamARN?: string|null,
     *   '@region'?: string|null,
     * }|SplitShardInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getNewStartingHashKey(): ?string
    {
        return $this->newStartingHashKey;
    }

    public function getShardToSplit(): ?string
    {
        return $this->shardToSplit;
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
            'X-Amz-Target' => 'Kinesis_20131202.SplitShard',
            'Accept' => 'application/json',
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

    public function setNewStartingHashKey(?string $value): self
    {
        $this->newStartingHashKey = $value;

        return $this;
    }

    public function setShardToSplit(?string $value): self
    {
        $this->shardToSplit = $value;

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
        if (null === $v = $this->shardToSplit) {
            throw new InvalidArgument(\sprintf('Missing parameter "ShardToSplit" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ShardToSplit'] = $v;
        if (null === $v = $this->newStartingHashKey) {
            throw new InvalidArgument(\sprintf('Missing parameter "NewStartingHashKey" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['NewStartingHashKey'] = $v;
        if (null !== $v = $this->streamArn) {
            $payload['StreamARN'] = $v;
        }

        return $payload;
    }
}
