<?php

namespace AsyncAws\CodeDeploy;

use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\CodeDeploy\Result\PutLifecycleEventHookExecutionStatusOutput;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\RequestContext;

class CodeDeployClient extends AbstractApi
{
    /**
     * Sets the result of a Lambda validation function. The function validates one or both lifecycle events
     * (`BeforeAllowTraffic` and `AfterAllowTraffic`) and returns `Succeeded` or `Failed`.
     *
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codedeploy-2014-10-06.html#putlifecycleeventhookexecutionstatus
     *
     * @param array{
     *   deploymentId?: string,
     *   lifecycleEventHookExecutionId?: string,
     *   status?: \AsyncAws\CodeDeploy\Enum\LifecycleEventStatus::*,
     *   @region?: string,
     * }|PutLifecycleEventHookExecutionStatusInput $input
     */
    public function putLifecycleEventHookExecutionStatus($input = []): PutLifecycleEventHookExecutionStatusOutput
    {
        $input = PutLifecycleEventHookExecutionStatusInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutLifecycleEventHookExecutionStatus', 'region' => $input->getRegion()]));

        return new PutLifecycleEventHookExecutionStatusOutput($response);
    }

    protected function getServiceCode(): string
    {
        return 'codedeploy';
    }

    protected function getSignatureScopeName(): string
    {
        return 'codedeploy';
    }

    protected function getSignatureVersion(): string
    {
        return 'v4';
    }
}
