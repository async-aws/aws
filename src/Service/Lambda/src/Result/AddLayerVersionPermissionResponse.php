<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class AddLayerVersionPermissionResponse extends Result
{
    /**
     * The permission statement.
     */
    private $statement;

    /**
     * A unique identifier for the current revision of the policy.
     */
    private $revisionId;

    public function getRevisionId(): ?string
    {
        $this->initialize();

        return $this->revisionId;
    }

    public function getStatement(): ?string
    {
        $this->initialize();

        return $this->statement;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->statement = isset($data['Statement']) ? (string) $data['Statement'] : null;
        $this->revisionId = isset($data['RevisionId']) ? (string) $data['RevisionId'] : null;
    }
}
