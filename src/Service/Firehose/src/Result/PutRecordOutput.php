<?php

namespace AsyncAws\Firehose\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PutRecordOutput extends Result
{
    /**
     * The ID of the record.
     */
    private $recordId;

    /**
     * Indicates whether server-side encryption (SSE) was enabled during this operation.
     */
    private $encrypted;

    public function getEncrypted(): ?bool
    {
        $this->initialize();

        return $this->encrypted;
    }

    public function getRecordId(): string
    {
        $this->initialize();

        return $this->recordId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->recordId = (string) $data['RecordId'];
        $this->encrypted = isset($data['Encrypted']) ? filter_var($data['Encrypted'], \FILTER_VALIDATE_BOOLEAN) : null;
    }
}
