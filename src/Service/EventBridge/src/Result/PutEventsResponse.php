<?php

namespace AsyncAws\EventBridge\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\EventBridge\ValueObject\PutEventsResultEntry;

class PutEventsResponse extends Result
{
    /**
     * The number of failed entries.
     */
    private $failedEntryCount;

    /**
     * The successfully and unsuccessfully ingested events results. If the ingestion was successful, the entry has the event
     * ID in it. Otherwise, you can use the error code and error message to identify the problem with the entry.
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

    /**
     * @return PutEventsResultEntry[]
     */
    private function populateResultPutEventsResultEntryList(array $json): array
    {
        $items = [];
        foreach ($json as $item) {
            $items[] = new PutEventsResultEntry([
                'EventId' => isset($item['EventId']) ? (string) $item['EventId'] : null,
                'ErrorCode' => isset($item['ErrorCode']) ? (string) $item['ErrorCode'] : null,
                'ErrorMessage' => isset($item['ErrorMessage']) ? (string) $item['ErrorMessage'] : null,
            ]);
        }

        return $items;
    }
}
