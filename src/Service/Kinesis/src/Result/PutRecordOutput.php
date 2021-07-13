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
     */
    private $shardId;

    /**
     * The sequence number identifier that was assigned to the put data record. The sequence number for the record is unique
     * across all records in the stream. A sequence number is the identifier associated with every record put into the
     * stream.
     */
    private $sequenceNumber;

    /**
     * The encryption type to use on the record. This parameter can be one of the following values:.
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
        $this->encryptionType = isset($data['EncryptionType']) ? (string) $data['EncryptionType'] : null;
    }
}
