<?php

namespace AsyncAws\Kinesis\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Kinesis\Enum\EncryptionType;
use AsyncAws\Kinesis\ValueObject\PutRecordsResultEntry;

/**
 * `PutRecords` results.
 */
class PutRecordsOutput extends Result
{
    /**
     * The number of unsuccessfully processed records in a `PutRecords` request.
     *
     * @var int|null
     */
    private $failedRecordCount;

    /**
     * An array of successfully and unsuccessfully processed record results. A record that is successfully added to a stream
     * includes `SequenceNumber` and `ShardId` in the result. A record that fails to be added to a stream includes
     * `ErrorCode` and `ErrorMessage` in the result.
     *
     * @var PutRecordsResultEntry[]
     */
    private $records;

    /**
     * The encryption type used on the records. This parameter can be one of the following values:.
     *
     * - `NONE`: Do not encrypt the records.
     * - `KMS`: Use server-side encryption on the records using a customer-managed Amazon Web Services KMS key.
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

    public function getFailedRecordCount(): ?int
    {
        $this->initialize();

        return $this->failedRecordCount;
    }

    /**
     * @return PutRecordsResultEntry[]
     */
    public function getRecords(): array
    {
        $this->initialize();

        return $this->records;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->failedRecordCount = isset($data['FailedRecordCount']) ? (int) $data['FailedRecordCount'] : null;
        $this->records = $this->populateResultPutRecordsResultEntryList($data['Records'] ?? []);
        $this->encryptionType = isset($data['EncryptionType']) ? (string) $data['EncryptionType'] : null;
    }

    private function populateResultPutRecordsResultEntry(array $json): PutRecordsResultEntry
    {
        return new PutRecordsResultEntry([
            'SequenceNumber' => isset($json['SequenceNumber']) ? (string) $json['SequenceNumber'] : null,
            'ShardId' => isset($json['ShardId']) ? (string) $json['ShardId'] : null,
            'ErrorCode' => isset($json['ErrorCode']) ? (string) $json['ErrorCode'] : null,
            'ErrorMessage' => isset($json['ErrorMessage']) ? (string) $json['ErrorMessage'] : null,
        ]);
    }

    /**
     * @return PutRecordsResultEntry[]
     */
    private function populateResultPutRecordsResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPutRecordsResultEntry($item);
        }

        return $items;
    }
}
