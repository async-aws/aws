<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\ProjectStatus;

class DeleteProjectResponse extends Result
{
    /**
     * The current status of the delete project operation.
     */
    private $Status;

    /**
     * @return ProjectStatus::*|null
     */
    public function getStatus(): ?string
    {
        $this->initialize();

        return $this->Status;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->Status = isset($data['Status']) ? (string) $data['Status'] : null;
    }
}
