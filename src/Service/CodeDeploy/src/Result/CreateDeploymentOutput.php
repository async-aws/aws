<?php

namespace AsyncAws\CodeDeploy\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Represents the output of a `CreateDeployment` operation.
 */
class CreateDeploymentOutput extends Result
{
    /**
     * The unique ID of a deployment.
     */
    private $deploymentId;

    public function getDeploymentId(): ?string
    {
        $this->initialize();

        return $this->deploymentId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->deploymentId = isset($data['deploymentId']) ? (string) $data['deploymentId'] : null;
    }
}
