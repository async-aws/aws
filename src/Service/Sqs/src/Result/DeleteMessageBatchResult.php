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
     */
    private $successful;

    /**
     * A list of `BatchResultErrorEntry` items.
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
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->DeleteMessageBatchResult;

        $this->successful = $this->populateResultDeleteMessageBatchResultEntryList($data->DeleteMessageBatchResultEntry);
        $this->failed = $this->populateResultBatchResultErrorEntryList($data->BatchResultErrorEntry);
    }

    /**
     * @return BatchResultErrorEntry[]
     */
    private function populateResultBatchResultErrorEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new BatchResultErrorEntry([
                'Id' => (string) $item->Id,
                'SenderFault' => filter_var((string) $item->SenderFault, \FILTER_VALIDATE_BOOLEAN),
                'Code' => (string) $item->Code,
                'Message' => ($v = $item->Message) ? (string) $v : null,
            ]);
        }

        return $items;
    }

    /**
     * @return DeleteMessageBatchResultEntry[]
     */
    private function populateResultDeleteMessageBatchResultEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new DeleteMessageBatchResultEntry([
                'Id' => (string) $item->Id,
            ]);
        }

        return $items;
    }
}
