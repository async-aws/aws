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
     *
     * @var PublishBatchResultEntry[]
     */
    private $successful;

    /**
     * A list of failed `PublishBatch` responses.
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

        $this->successful = (0 === ($v = $data->Successful)->count()) ? [] : $this->populateResultPublishBatchResultEntryList($v);
        $this->failed = (0 === ($v = $data->Failed)->count()) ? [] : $this->populateResultBatchResultErrorEntryList($v);
    }

    private function populateResultBatchResultErrorEntry(\SimpleXMLElement $xml): BatchResultErrorEntry
    {
        return new BatchResultErrorEntry([
            'Id' => (string) $xml->Id,
            'Code' => (string) $xml->Code,
            'Message' => (null !== $v = $xml->Message[0]) ? (string) $v : null,
            'SenderFault' => filter_var((string) $xml->SenderFault, \FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    /**
     * @return BatchResultErrorEntry[]
     */
    private function populateResultBatchResultErrorEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultBatchResultErrorEntry($item);
        }

        return $items;
    }

    private function populateResultPublishBatchResultEntry(\SimpleXMLElement $xml): PublishBatchResultEntry
    {
        return new PublishBatchResultEntry([
            'Id' => (null !== $v = $xml->Id[0]) ? (string) $v : null,
            'MessageId' => (null !== $v = $xml->MessageId[0]) ? (string) $v : null,
            'SequenceNumber' => (null !== $v = $xml->SequenceNumber[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return PublishBatchResultEntry[]
     */
    private function populateResultPublishBatchResultEntryList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->member as $item) {
            $items[] = $this->populateResultPublishBatchResultEntry($item);
        }

        return $items;
    }
}
