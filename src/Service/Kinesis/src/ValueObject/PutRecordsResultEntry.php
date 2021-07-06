<?php

namespace AsyncAws\Kinesis\ValueObject;

/**
 * Represents the result of an individual record from a `PutRecords` request. A record that is successfully added to a
 * stream includes `SequenceNumber` and `ShardId` in the result. A record that fails to be added to the stream includes
 * `ErrorCode` and `ErrorMessage` in the result.
 */
final class PutRecordsResultEntry
{
    /**
     * The sequence number for an individual record result.
     */
    private $sequenceNumber;

    /**
     * The shard ID for an individual record result.
     */
    private $shardId;

    /**
     * The error code for an individual record result. `ErrorCodes` can be either `ProvisionedThroughputExceededException`
     * or `InternalFailure`.
     */
    private $errorCode;

    /**
     * The error message for an individual record result. An `ErrorCode` value of `ProvisionedThroughputExceededException`
     * has an error message that includes the account ID, stream name, and shard ID. An `ErrorCode` value of
     * `InternalFailure` has the error message `"Internal Service Failure"`.
     */
    private $errorMessage;

    /**
     * @param array{
     *   SequenceNumber?: null|string,
     *   ShardId?: null|string,
     *   ErrorCode?: null|string,
     *   ErrorMessage?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sequenceNumber = $input['SequenceNumber'] ?? null;
        $this->shardId = $input['ShardId'] ?? null;
        $this->errorCode = $input['ErrorCode'] ?? null;
        $this->errorMessage = $input['ErrorMessage'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }

    public function getShardId(): ?string
    {
        return $this->shardId;
    }
}
