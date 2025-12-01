<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\Enum\SchemaStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class GetSchemaCreationStatusResponse extends Result
{
    /**
     * The current state of the schema (PROCESSING, FAILED, SUCCESS, or NOT_APPLICABLE). When the schema is in the ACTIVE
     * state, you can add data.
     *
     * @var SchemaStatus::*|null
     */
    private $status;

    /**
     * Detailed information about the status of the schema creation operation.
     *
     * @var string|null
     */
    private $details;

    public function getDetails(): ?string
    {
        $this->initialize();

        return $this->details;
    }

    /**
     * @return SchemaStatus::*|null
     */
    public function getStatus(): ?string
    {
        $this->initialize();

        return $this->status;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->status = isset($data['status']) ? (!SchemaStatus::exists((string) $data['status']) ? SchemaStatus::UNKNOWN_TO_SDK : (string) $data['status']) : null;
        $this->details = isset($data['details']) ? (string) $data['details'] : null;
    }
}
