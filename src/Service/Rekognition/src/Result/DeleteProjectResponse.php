<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Rekognition\Enum\ProjectStatus;

class DeleteProjectResponse extends Result
{
    /**
     * The current status of the delete project operation.
     *
     * @var ProjectStatus::*|string|null
     */
    private $status;

    /**
     * @return ProjectStatus::*|string|null
     */
    public function getStatus(): ?string
    {
        $this->initialize();

        return $this->status;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->status = isset($data['Status']) ? (string) $data['Status'] : null;
    }
}
