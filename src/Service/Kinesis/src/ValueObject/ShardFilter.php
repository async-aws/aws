<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kinesis\Enum\ShardFilterType;

/**
 * The request parameter used to filter out the response of the `ListShards` API.
 */
final class ShardFilter
{
    /**
     * The shard type specified in the `ShardFilter` parameter. This is a required property of the `ShardFilter` parameter.
     *
     * You can specify the following valid values:
     *
     * - `AFTER_SHARD_ID` - the response includes all the shards, starting with the shard whose ID immediately follows the
     *   `ShardId` that you provided.
     * - `AT_TRIM_HORIZON` - the response includes all the shards that were open at `TRIM_HORIZON`.
     * - `FROM_TRIM_HORIZON` - (default), the response includes all the shards within the retention period of the data
     *   stream (trim to tip).
     * - `AT_LATEST` - the response includes only the currently open shards of the data stream.
     * - `AT_TIMESTAMP` - the response includes all shards whose start timestamp is less than or equal to the given
     *   timestamp and end timestamp is greater than or equal to the given timestamp or still open.
     * - `FROM_TIMESTAMP` - the response incldues all closed shards whose end timestamp is greater than or equal to the
     *   given timestamp and also all open shards. Corrected to `TRIM_HORIZON` of the data stream if `FROM_TIMESTAMP` is
     *   less than the `TRIM_HORIZON` value.
     */
    private $type;

    /**
     * The exclusive start `shardID` speified in the `ShardFilter` parameter. This property can only be used if the
     * `AFTER_SHARD_ID` shard type is specified.
     */
    private $shardId;

    /**
     * The timestamps specified in the `ShardFilter` parameter. A timestamp is a Unix epoch date with precision in
     * milliseconds. For example, 2016-04-04T19:58:46.480-00:00 or 1459799926.480. This property can only be used if
     * `FROM_TIMESTAMP` or `AT_TIMESTAMP` shard types are specified.
     */
    private $timestamp;

    /**
     * @param array{
     *   Type: ShardFilterType::*,
     *   ShardId?: null|string,
     *   Timestamp?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? $this->throwException(new InvalidArgument('Missing required field "Type".'));
        $this->shardId = $input['ShardId'] ?? null;
        $this->timestamp = $input['Timestamp'] ?? null;
    }

    /**
     * @param array{
     *   Type: ShardFilterType::*,
     *   ShardId?: null|string,
     *   Timestamp?: null|\DateTimeImmutable,
     * }|ShardFilter $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getShardId(): ?string
    {
        return $this->shardId;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @return ShardFilterType::*
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->type;
        if (!ShardFilterType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "ShardFilterType".', __CLASS__, $v));
        }
        $payload['Type'] = $v;
        if (null !== $v = $this->shardId) {
            $payload['ShardId'] = $v;
        }
        if (null !== $v = $this->timestamp) {
            $payload['Timestamp'] = $v->getTimestamp();
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
