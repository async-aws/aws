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
    private $FailedEntryCount;

    /**
     * The successfully and unsuccessfully ingested events results. If the ingestion was successful, the entry has the event
     * ID in it. Otherwise, you can use the error code and error message to identify the problem with the entry.
     */
    private $Entries = [];

    /**
     * @return PutEventsResultEntry[]
     */
    public function getEntries(): array
    {
        $this->initialize();

        return $this->Entries;
    }

    public function getFailedEntryCount(): ?int
    {
        $this->initialize();

        return $this->FailedEntryCount;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->FailedEntryCount = isset($data['FailedEntryCount']) ? (int) $data['FailedEntryCount'] : null;
        $this->Entries = empty($data['Entries']) ? [] : (function (array $json): array {
            $items = [];
            foreach ($json as $item) {
                $items[] = new PutEventsResultEntry([
                    'EventId' => isset($item['EventId']) ? (string) $item['EventId'] : null,
                    'ErrorCode' => isset($item['ErrorCode']) ? (string) $item['ErrorCode'] : null,
                    'ErrorMessage' => isset($item['ErrorMessage']) ? (string) $item['ErrorMessage'] : null,
                ]);
            }

            return $items;
        })($data['Entries']);
    }
}
