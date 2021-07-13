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
     */
    private $failedRecordCount;

    /**
     * An array of successfully and unsuccessfully processed record results, correlated with the request by natural
     * ordering. A record that is successfully added to a stream includes `SequenceNumber` and `ShardId` in the result. A
     * record that fails to be added to a stream includes `ErrorCode` and `ErrorMessage` in the result.
     */
    private $records = [];

    /**
     * The encryption type used on the records. This parameter can be one of the following values:.
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
        $this->records = $this->populateResultPutRecordsResultEntryList($data['Records']);
        $this->encryptionType = isset($data['EncryptionType']) ? (string) $data['EncryptionType'] : null;
    }

    /**
     * @return PutRecordsResultEntry[]
     */
    private function populateResultPutRecordsResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new PutRecordsResultEntry([
                'SequenceNumber' => isset($item['SequenceNumber']) ? (string) $item['SequenceNumber'] : null,
                'ShardId' => isset($item['ShardId']) ? (string) $item['ShardId'] : null,
                'ErrorCode' => isset($item['ErrorCode']) ? (string) $item['ErrorCode'] : null,
                'ErrorMessage' => isset($item['ErrorMessage']) ? (string) $item['ErrorMessage'] : null,
            ]);
        }

        return $items;
    }
}
