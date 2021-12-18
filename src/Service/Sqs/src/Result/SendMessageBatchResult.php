<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sqs\ValueObject\BatchResultErrorEntry;
use AsyncAws\Sqs\ValueObject\SendMessageBatchResultEntry;

/**
 * For each message in the batch, the response contains a `SendMessageBatchResultEntry` tag if the message succeeds or a
 * `BatchResultErrorEntry` tag if the message fails.
 */
class SendMessageBatchResult extends Result
{
    /**
     * A list of `SendMessageBatchResultEntry` items.
     */
    private $successful;

    /**
     * A list of `BatchResultErrorEntry` items with error details about each message that can't be enqueued.
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
     * @return SendMessageBatchResultEntry[]
     */
    public function getSuccessful(): array
    {
        $this->initialize();

        return $this->successful;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->SendMessageBatchResult;

        $this->successful = $this->populateResultSendMessageBatchResultEntryList($data->SendMessageBatchResultEntry);
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
     * @return SendMessageBatchResultEntry[]
     */
    private function populateResultSendMessageBatchResultEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new SendMessageBatchResultEntry([
                'Id' => (string) $item->Id,
                'MessageId' => (string) $item->MessageId,
                'MD5OfMessageBody' => (string) $item->MD5OfMessageBody,
                'MD5OfMessageAttributes' => ($v = $item->MD5OfMessageAttributes) ? (string) $v : null,
                'MD5OfMessageSystemAttributes' => ($v = $item->MD5OfMessageSystemAttributes) ? (string) $v : null,
                'SequenceNumber' => ($v = $item->SequenceNumber) ? (string) $v : null,
            ]);
        }

        return $items;
    }
}
