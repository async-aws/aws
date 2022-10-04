<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a put repository triggers operation.
 */
class PutRepositoryTriggersOutput extends Result
{
    /**
     * The system-generated unique ID for the create or update operation.
     */
    private $configurationId;

    public function getConfigurationId(): ?string
    {
        $this->initialize();

        return $this->configurationId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->configurationId = isset($data['configurationId']) ? (string) $data['configurationId'] : null;
    }
}
