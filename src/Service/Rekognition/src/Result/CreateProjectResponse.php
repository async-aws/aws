<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateProjectResponse extends Result
{
    /**
     * The Amazon Resource Name (ARN) of the new project. You can use the ARN to configure IAM access to the project.
     */
    private $ProjectArn;

    public function getProjectArn(): ?string
    {
        $this->initialize();

        return $this->ProjectArn;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->ProjectArn = isset($data['ProjectArn']) ? (string) $data['ProjectArn'] : null;
    }
}
