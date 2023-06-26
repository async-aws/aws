<?php

namespace AsyncAws\TimestreamWrite\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\TimestreamWrite\ValueObject\RecordsIngested;

class WriteRecordsResponse extends Result
{
    /**
     * Information on the records ingested by this request.
     *
     * @var RecordsIngested|null
     */
    private $recordsIngested;

    public function getRecordsIngested(): ?RecordsIngested
    {
        $this->initialize();

        return $this->recordsIngested;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->recordsIngested = empty($data['RecordsIngested']) ? null : $this->populateResultRecordsIngested($data['RecordsIngested']);
    }

    private function populateResultRecordsIngested(array $json): RecordsIngested
    {
        return new RecordsIngested([
            'Total' => isset($json['Total']) ? (int) $json['Total'] : null,
            'MemoryStore' => isset($json['MemoryStore']) ? (int) $json['MemoryStore'] : null,
            'MagneticStore' => isset($json['MagneticStore']) ? (int) $json['MagneticStore'] : null,
        ]);
    }
}
