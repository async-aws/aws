<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Enum\EncryptionType;

/**
 * Represents the output for `PutRecord`.
 */
class PutRecordOutput extends Result
{
    /**
     * The shard ID of the shard where the data record was placed.
     *
     * @var string
     */
    private $shardId;

    /**
     * The sequence number identifier that was assigned to the put data record. The sequence number for the record is unique
     * across all records in the stream. A sequence number is the identifier associated with every record put into the
     * stream.
     *
     * @var string
     */
    private $sequenceNumber;

    /**
     * The encryption type to use on the record. This parameter can be one of the following values:
     *
     * - `NONE`: Do not encrypt the records in the stream.
     * - `KMS`: Use server-side encryption on the records in the stream using a customer-managed Amazon Web Services KMS
     *   key.
     *
     * @var EncryptionType::*|null
     */
    private $encryptionType;

    /**
     * @return EncryptionType::*|null
     */
    public function getEncryptionType(): ?string
    {
        $this->initialize();

        return $this->encryptionType;
    }

    public function getSequenceNumber(): string
    {
        $this->initialize();

        return $this->sequenceNumber;
    }

    public function getShardId(): string
    {
        $this->initialize();

        return $this->shardId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->shardId = (string) $data['ShardId'];
        $this->sequenceNumber = (string) $data['SequenceNumber'];
        $this->encryptionType = isset($data['EncryptionType']) ? (!EncryptionType::exists((string) $data['EncryptionType']) ? EncryptionType::UNKNOWN_TO_SDK : (string) $data['EncryptionType']) : null;
    }
}
