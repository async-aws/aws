<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kinesis\Enum\ShardFilterType;

/**
 * Enables you to filter out the response of the `ListShards` API. You can only specify one filter at a time.
 * If you use the `ShardFilter` parameter when invoking the ListShards API, the `Type` is the required property and must
 * be specified. If you specify the `AT_TRIM_HORIZON`, `FROM_TRIM_HORIZON`, or `AT_LATEST` types, you do not need to
 * specify either the `ShardId` or the `Timestamp` optional properties.
 * If you specify the `AFTER_SHARD_ID` type, you must also provide the value for the optional `ShardId` property. The
 * `ShardId` property is identical in fuctionality to the `ExclusiveStartShardId` parameter of the `ListShards` API.
 * When `ShardId` property is specified, the response includes the shards starting with the shard whose ID immediately
 * follows the `ShardId` that you provided.
 * If you specify the `AT_TIMESTAMP` or `FROM_TIMESTAMP_ID` type, you must also provide the value for the optional
 * `Timestamp` property. If you specify the AT_TIMESTAMP type, then all shards that were open at the provided timestamp
 * are returned. If you specify the FROM_TIMESTAMP type, then all shards starting from the provided timestamp to TIP are
 * returned.
 */
final class ShardFilter
{
    /**
     * The shard type specified in the `ShardFilter` parameter. This is a required property of the `ShardFilter` parameter.
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
        $this->type = $input['Type'] ?? null;
        $this->shardId = $input['ShardId'] ?? null;
        $this->timestamp = $input['Timestamp'] ?? null;
    }

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
        if (null === $v = $this->type) {
            throw new InvalidArgument(sprintf('Missing parameter "Type" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ShardFilterType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "Type" for "%s". The value "%s" is not a valid "ShardFilterType".', __CLASS__, $v));
        }
        $payload['Type'] = $v;
        if (null !== $v = $this->shardId) {
            $payload['ShardId'] = $v;
        }
        if (null !== $v = $this->timestamp) {
            $payload['Timestamp'] = $v->format(\DateTimeInterface::ATOM);
        }

        return $payload;
    }
}
