<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Kinesis\Enum\EncryptionType;
use AsyncAws\Kinesis\Enum\StreamStatus;

/**
 * Represents the output for DescribeStream.
 */
final class StreamDescription
{
    /**
     * The name of the stream being described.
     */
    private $streamName;

    /**
     * The Amazon Resource Name (ARN) for the stream being described.
     */
    private $streamArn;

    /**
     * The current status of the stream being described. The stream status is one of the following states:.
     *
     * - `CREATING` - The stream is being created. Kinesis Data Streams immediately returns and sets `StreamStatus` to
     *   `CREATING`.
     * - `DELETING` - The stream is being deleted. The specified stream is in the `DELETING` state until Kinesis Data
     *   Streams completes the deletion.
     * - `ACTIVE` - The stream exists and is ready for read and write operations or deletion. You should perform read and
     *   write operations only on an `ACTIVE` stream.
     * - `UPDATING` - Shards in the stream are being merged or split. Read and write operations continue to work while the
     *   stream is in the `UPDATING` state.
     */
    private $streamStatus;

    /**
     * Specifies the capacity mode to which you want to set your data stream. Currently, in Kinesis Data Streams, you can
     * choose between an **on-demand** capacity mode and a **provisioned** capacity mode for your data streams.
     */
    private $streamModeDetails;

    /**
     * The shards that comprise the stream.
     */
    private $shards;

    /**
     * If set to `true`, more shards in the stream are available to describe.
     */
    private $hasMoreShards;

    /**
     * The current retention period, in hours. Minimum value of 24. Maximum value of 168.
     */
    private $retentionPeriodHours;

    /**
     * The approximate time that the stream was created.
     */
    private $streamCreationTimestamp;

    /**
     * Represents the current enhanced monitoring settings of the stream.
     */
    private $enhancedMonitoring;

    /**
     * The server-side encryption type used on the stream. This parameter can be one of the following values:.
     *
     * - `NONE`: Do not encrypt the records in the stream.
     * - `KMS`: Use server-side encryption on the records in the stream using a customer-managed Amazon Web Services KMS
     *   key.
     */
    private $encryptionType;

    /**
     * The GUID for the customer-managed Amazon Web Services KMS key to use for encryption. This value can be a globally
     * unique identifier, a fully specified ARN to either an alias or a key, or an alias name prefixed by "alias/".You can
     * also use a master key owned by Kinesis Data Streams by specifying the alias `aws/kinesis`.
     *
     * - Key ARN example: `arn:aws:kms:us-east-1:123456789012:key/12345678-1234-1234-1234-123456789012`
     * - Alias ARN example: `arn:aws:kms:us-east-1:123456789012:alias/MyAliasName`
     * - Globally unique key ID example: `12345678-1234-1234-1234-123456789012`
     * - Alias name example: `alias/MyAliasName`
     * - Master key owned by Kinesis Data Streams: `alias/aws/kinesis`
     */
    private $keyId;

    /**
     * @param array{
     *   StreamName: string,
     *   StreamARN: string,
     *   StreamStatus: StreamStatus::*,
     *   StreamModeDetails?: null|StreamModeDetails|array,
     *   Shards: Shard[],
     *   HasMoreShards: bool,
     *   RetentionPeriodHours: int,
     *   StreamCreationTimestamp: \DateTimeImmutable,
     *   EnhancedMonitoring: EnhancedMetrics[],
     *   EncryptionType?: null|EncryptionType::*,
     *   KeyId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->streamName = $input['StreamName'] ?? null;
        $this->streamArn = $input['StreamARN'] ?? null;
        $this->streamStatus = $input['StreamStatus'] ?? null;
        $this->streamModeDetails = isset($input['StreamModeDetails']) ? StreamModeDetails::create($input['StreamModeDetails']) : null;
        $this->shards = isset($input['Shards']) ? array_map([Shard::class, 'create'], $input['Shards']) : null;
        $this->hasMoreShards = $input['HasMoreShards'] ?? null;
        $this->retentionPeriodHours = $input['RetentionPeriodHours'] ?? null;
        $this->streamCreationTimestamp = $input['StreamCreationTimestamp'] ?? null;
        $this->enhancedMonitoring = isset($input['EnhancedMonitoring']) ? array_map([EnhancedMetrics::class, 'create'], $input['EnhancedMonitoring']) : null;
        $this->encryptionType = $input['EncryptionType'] ?? null;
        $this->keyId = $input['KeyId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return EncryptionType::*|null
     */
    public function getEncryptionType(): ?string
    {
        return $this->encryptionType;
    }

    /**
     * @return EnhancedMetrics[]
     */
    public function getEnhancedMonitoring(): array
    {
        return $this->enhancedMonitoring ?? [];
    }

    public function getHasMoreShards(): bool
    {
        return $this->hasMoreShards;
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    public function getRetentionPeriodHours(): int
    {
        return $this->retentionPeriodHours;
    }

    /**
     * @return Shard[]
     */
    public function getShards(): array
    {
        return $this->shards ?? [];
    }

    public function getStreamArn(): string
    {
        return $this->streamArn;
    }

    public function getStreamCreationTimestamp(): \DateTimeImmutable
    {
        return $this->streamCreationTimestamp;
    }

    public function getStreamModeDetails(): ?StreamModeDetails
    {
        return $this->streamModeDetails;
    }

    public function getStreamName(): string
    {
        return $this->streamName;
    }

    /**
     * @return StreamStatus::*
     */
    public function getStreamStatus(): string
    {
        return $this->streamStatus;
    }
}
