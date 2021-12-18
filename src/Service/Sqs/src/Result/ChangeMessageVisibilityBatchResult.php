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
     * @return ChangeMessageVisibilityBatchResultEntry[]
     */
    public function getSuccessful(): array
    {
        $this->initialize();

        return $this->successful;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->ChangeMessageVisibilityBatchResult;

        $this->successful = $this->populateResultChangeMessageVisibilityBatchResultEntryList($data->ChangeMessageVisibilityBatchResultEntry);
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
     * @return ChangeMessageVisibilityBatchResultEntry[]
     */
    private function populateResultChangeMessageVisibilityBatchResultEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new ChangeMessageVisibilityBatchResultEntry([
                'Id' => (string) $item->Id,
            ]);
        }

        return $items;
    }
}
