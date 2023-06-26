<?php

namespace AsyncAws\CodeCommit\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a delete repository operation.
 */
class DeleteRepositoryOutput extends Result
{
    /**
     * The ID of the repository that was deleted.
     *
     * @var string|null
     */
    private $repositoryId;

    public function getRepositoryId(): ?string
    {
        $this->initialize();

        return $this->repositoryId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->repositoryId = isset($data['repositoryId']) ? (string) $data['repositoryId'] : null;
    }
}
