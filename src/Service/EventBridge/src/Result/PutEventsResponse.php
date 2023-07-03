<?php

namespace AsyncAws\EventBridge\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\EventBridge\ValueObject\PutEventsResultEntry;

class PutEventsResponse extends Result
{
    /**
     * The number of failed entries.
     *
     * @var int|null
     */
    private $failedEntryCount;

    /**
     * The successfully and unsuccessfully ingested events results. If the ingestion was successful, the entry has the event
     * ID in it. Otherwise, you can use the error code and error message to identify the problem with the entry.
     *
     * For each record, the index of the response element is the same as the index in the request array.
     *
     * @var PutEventsResultEntry[]
     */
    private $entries;

    /**
     * @return PutEventsResultEntry[]
     */
    public function getEntries(): array
    {
        $this->initialize();

        return $this->entries;
    }

    public function getFailedEntryCount(): ?int
    {
        $this->initialize();

        return $this->failedEntryCount;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->failedEntryCount = isset($data['FailedEntryCount']) ? (int) $data['FailedEntryCount'] : null;
        $this->entries = empty($data['Entries']) ? [] : $this->populateResultPutEventsResultEntryList($data['Entries']);
    }

    private function populateResultPutEventsResultEntry(array $json): PutEventsResultEntry
    {
        return new PutEventsResultEntry([
            'EventId' => isset($json['EventId']) ? (string) $json['EventId'] : null,
            'ErrorCode' => isset($json['ErrorCode']) ? (string) $json['ErrorCode'] : null,
            'ErrorMessage' => isset($json['ErrorMessage']) ? (string) $json['ErrorMessage'] : null,
        ]);
    }

    /**
     * @return PutEventsResultEntry[]
     */
    private function populateResultPutEventsResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = $this->populateResultPutEventsResultEntry($item);
        }

        return $items;
    }
}
