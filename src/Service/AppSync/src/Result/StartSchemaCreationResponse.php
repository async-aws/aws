<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\AppSync\Enum\SchemaStatus;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartSchemaCreationResponse extends Result
{
    /**
     * The current state of the schema (PROCESSING, FAILED, SUCCESS, or NOT_APPLICABLE). When the schema is in the ACTIVE
     * state, you can add data.
     *
     * @var SchemaStatus::*|string|null
     */
    private $status;

    /**
     * @return SchemaStatus::*|string|null
     */
    public function getStatus(): ?string
    {
        $this->initialize();

        return $this->status;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->status = isset($data['status']) ? (string) $data['status'] : null;
    }
}
