<?php

namespace AsyncAws\CodeDeploy\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PutLifecycleEventHookExecutionStatusOutput extends Result
{
    /**
     * The execution ID of the lifecycle event hook. A hook is specified in the `hooks` section of the deployment's AppSpec
     * file.
     */
    private $lifecycleEventHookExecutionId;

    public function getLifecycleEventHookExecutionId(): ?string
    {
        $this->initialize();

        return $this->lifecycleEventHookExecutionId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->lifecycleEventHookExecutionId = isset($data['lifecycleEventHookExecutionId']) ? (string) $data['lifecycleEventHookExecutionId'] : null;
    }
}
