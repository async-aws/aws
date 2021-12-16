<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Sns\ValueObject\BatchResultErrorEntry;
use AsyncAws\Sns\ValueObject\PublishBatchResultEntry;

class PublishBatchResponse extends Result
{
    /**
     * A list of successful `PublishBatch` responses.
     */
    private $successful;

    /**
     * A list of failed `PublishBatch` responses.
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
     * @return PublishBatchResultEntry[]
     */
    public function getSuccessful(): array
    {
        $this->initialize();

        return $this->successful;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->PublishBatchResult;

        $this->successful = !$data->Successful ? [] : $this->populateResultPublishBatchResultEntryList($data->Successful);
        $this->failed = !$data->Failed ? [] : $this->populateResultBatchResultErrorEntryList($data->Failed);
    }

    /**
     * @return BatchResultErrorEntry[]
     */
    private function populateResultBatchResultErrorEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new BatchResultErrorEntry([
                'Id' => (string) $item->Id,
                'Code' => (string) $item->Code,
                'Message' => ($v = $item->Message) ? (string) $v : null,
                'SenderFault' => filter_var((string) $item->SenderFault, \FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        return $items;
    }

    /**
     * @return PublishBatchResultEntry[]
     */
    private function populateResultPublishBatchResultEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = new PublishBatchResultEntry([
                'Id' => ($v = $item->Id) ? (string) $v : null,
                'MessageId' => ($v = $item->MessageId) ? (string) $v : null,
                'SequenceNumber' => ($v = $item->SequenceNumber) ? (string) $v : null,
            ]);
        }

        return $items;
    }
}
