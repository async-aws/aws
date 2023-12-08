<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\ValueObject\BatchResultErrorEntry;
use AsyncAws\Sqs\ValueObject\ChangeMessageVisibilityBatchResultEntry;

/**
 * For each message in the batch, the response contains a `ChangeMessageVisibilityBatchResultEntry` tag if the message
 * succeeds or a `BatchResultErrorEntry` tag if the message fails.
 */
class ChangeMessageVisibilityBatchResult extends Result
{
    /**
     * A list of `ChangeMessageVisibilityBatchResultEntry` items.
     *
     * @var ChangeMessageVisibilityBatchResultEntry[]
     */
    private $successful;

    /**
     * A list of `BatchResultErrorEntry` items.
     *
     * @var BatchResultErrorEntry[]
     */
    private $failed;

    /**
     * @return BatchResultErrorEntry[]
     */
    public function getFailed(): array
    {
        $this->initialize();

        return $this->failed;
    }

    /**
     * @return ChangeMessageVisibilityBatchResultEntry[]
     */
    public function getSuccessful(): array
    {
        $this->initialize();

        return $this->successful;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->successful = $this->populateResultChangeMessageVisibilityBatchResultEntryList($data['Successful'] ?? []);
        $this->failed = $this->populateResultBatchResultErrorEntryList($data['Failed'] ?? []);
    }

    private function populateResultBatchResultErrorEntry(array $json): BatchResultErrorEntry
    {
        return new BatchResultErrorEntry([
            'Id' => (string) $json['Id'],
            'SenderFault' => filter_var($json['SenderFault'], \FILTER_VALIDATE_BOOLEAN),
            'Code' => (string) $json['Code'],
            'Message' => isset($json['Message']) ? (string) $json['Message'] : null,
        ]);
    }

    /**
     * @return BatchResultErrorEntry[]
     */
    private function populateResultBatchResultErrorEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultBatchResultErrorEntry($item);
        }

        return $items;
    }

    private function populateResultChangeMessageVisibilityBatchResultEntry(array $json): ChangeMessageVisibilityBatchResultEntry
    {
        return new ChangeMessageVisibilityBatchResultEntry([
            'Id' => (string) $json['Id'],
        ]);
    }

    /**
     * @return ChangeMessageVisibilityBatchResultEntry[]
     */
    private function populateResultChangeMessageVisibilityBatchResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultChangeMessageVisibilityBatchResultEntry($item);
        }

        return $items;
    }
}
