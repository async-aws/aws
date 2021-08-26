<?php

namespace AsyncAws\Firehose\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Firehose\ValueObject\PutRecordBatchResponseEntry;

class PutRecordBatchOutput extends Result
{
    /**
     * The number of records that might have failed processing. This number might be greater than 0 even if the
     * PutRecordBatch call succeeds. Check `FailedPutCount` to determine whether there are records that you need to resend.
     */
    private $failedPutCount;

    /**
     * Indicates whether server-side encryption (SSE) was enabled during this operation.
     */
    private $encrypted;

    /**
     * The results array. For each record, the index of the response element is the same as the index used in the request
     * array.
     */
    private $requestResponses = [];

    public function getEncrypted(): ?bool
    {
        $this->initialize();

        return $this->encrypted;
    }

    public function getFailedPutCount(): int
    {
        $this->initialize();

        return $this->failedPutCount;
    }

    /**
     * @return PutRecordBatchResponseEntry[]
     */
    public function getRequestResponses(): array
    {
        $this->initialize();

        return $this->requestResponses;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->failedPutCount = (int) $data['FailedPutCount'];
        $this->encrypted = isset($data['Encrypted']) ? filter_var($data['Encrypted'], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->requestResponses = $this->populateResultPutRecordBatchResponseEntryList($data['RequestResponses']);
    }

    /**
     * @return PutRecordBatchResponseEntry[]
     */
    private function populateResultPutRecordBatchResponseEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new PutRecordBatchResponseEntry([
                'RecordId' => isset($item['RecordId']) ? (string) $item['RecordId'] : null,
                'ErrorCode' => isset($item['ErrorCode']) ? (string) $item['ErrorCode'] : null,
                'ErrorMessage' => isset($item['ErrorMessage']) ? (string) $item['ErrorMessage'] : null,
            ]);
        }

        return $items;
    }
}
