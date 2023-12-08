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
     *
     * @var SendMessageBatchResultEntry[]
     */
    private $successful;

    /**
     * A list of `BatchResultErrorEntry` items with error details about each message that can't be enqueued.
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
     * @return SendMessageBatchResultEntry[]
     */
    public function getSuccessful(): array
    {
        $this->initialize();

        return $this->successful;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->successful = $this->populateResultSendMessageBatchResultEntryList($data['Successful'] ?? []);
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

    private function populateResultSendMessageBatchResultEntry(array $json): SendMessageBatchResultEntry
    {
        return new SendMessageBatchResultEntry([
            'Id' => (string) $json['Id'],
            'MessageId' => (string) $json['MessageId'],
            'MD5OfMessageBody' => (string) $json['MD5OfMessageBody'],
            'MD5OfMessageAttributes' => isset($json['MD5OfMessageAttributes']) ? (string) $json['MD5OfMessageAttributes'] : null,
            'MD5OfMessageSystemAttributes' => isset($json['MD5OfMessageSystemAttributes']) ? (string) $json['MD5OfMessageSystemAttributes'] : null,
            'SequenceNumber' => isset($json['SequenceNumber']) ? (string) $json['SequenceNumber'] : null,
        ]);
    }

    /**
     * @return SendMessageBatchResultEntry[]
     */
    private function populateResultSendMessageBatchResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultSendMessageBatchResultEntry($item);
        }

        return $items;
    }
}
