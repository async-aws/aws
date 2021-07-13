<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Kinesis\Enum\EncryptionType;

/**
 * The unit of data of the Kinesis data stream, which is composed of a sequence number, a partition key, and a data
 * blob.
 */
final class Record
{
    /**
     * The unique identifier of the record within its shard.
     */
    private $sequenceNumber;

    /**
     * The approximate time that the record was inserted into the stream.
     */
    private $approximateArrivalTimestamp;

    /**
     * The data blob. The data in the blob is both opaque and immutable to Kinesis Data Streams, which does not inspect,
     * interpret, or change the data in the blob in any way. When the data blob (the payload before base64-encoding) is
     * added to the partition key size, the total size must not exceed the maximum record size (1 MB).
     */
    private $data;

    /**
     * Identifies which shard in the stream the data record is assigned to.
     */
    private $partitionKey;

    /**
     * The encryption type used on the record. This parameter can be one of the following values:.
     */
    private $encryptionType;

    /**
     * @param array{
     *   SequenceNumber: string,
     *   ApproximateArrivalTimestamp?: null|\DateTimeImmutable,
     *   Data: string,
     *   PartitionKey: string,
     *   EncryptionType?: null|EncryptionType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->sequenceNumber = $input['SequenceNumber'] ?? null;
        $this->approximateArrivalTimestamp = $input['ApproximateArrivalTimestamp'] ?? null;
        $this->data = $input['Data'] ?? null;
        $this->partitionKey = $input['PartitionKey'] ?? null;
        $this->encryptionType = $input['EncryptionType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getApproximateArrivalTimestamp(): ?\DateTimeImmutable
    {
        return $this->approximateArrivalTimestamp;
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @return EncryptionType::*|null
     */
    public function getEncryptionType(): ?string
    {
        return $this->encryptionType;
    }

    public function getPartitionKey(): string
    {
        return $this->partitionKey;
    }

    public function getSequenceNumber(): string
    {
        return $this->sequenceNumber;
    }
}
