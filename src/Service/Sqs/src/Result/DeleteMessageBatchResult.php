<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\ValueObject\BatchResultErrorEntry;
use AsyncAws\Sqs\ValueObject\DeleteMessageBatchResultEntry;

/**
 * For each message in the batch, the response contains a `DeleteMessageBatchResultEntry` tag if the message is deleted
 * or a `BatchResultErrorEntry` tag if the message can't be deleted.
 */
class DeleteMessageBatchResult extends Result
{
    /**
     * A list of `DeleteMessageBatchResultEntry` items.
     *
     * @var DeleteMessageBatchResultEntry[]
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
     * @return DeleteMessageBatchResultEntry[]
     */
    public function getSuccessful(): array
    {
        $this->initialize();

        return $this->successful;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->successful = $this->populateResultDeleteMessageBatchResultEntryList($data['Successful'] ?? []);
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

    private function populateResultDeleteMessageBatchResultEntry(array $json): DeleteMessageBatchResultEntry
    {
        return new DeleteMessageBatchResultEntry([
            'Id' => (string) $json['Id'],
        ]);
    }

    /**
     * @return DeleteMessageBatchResultEntry[]
     */
    private function populateResultDeleteMessageBatchResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultDeleteMessageBatchResultEntry($item);
        }

        return $items;
    }
}
