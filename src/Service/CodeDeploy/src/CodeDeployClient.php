<?php

namespace AsyncAws\CodeDeploy;

use AsyncAws\CodeDeploy\Enum\LifecycleEventStatus;
use AsyncAws\CodeDeploy\Exception\DeploymentDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\DeploymentIdRequiredException;
use AsyncAws\CodeDeploy\Exception\InvalidDeploymentIdException;
use AsyncAws\CodeDeploy\Exception\InvalidLifecycleEventHookExecutionIdException;
use AsyncAws\CodeDeploy\Exception\InvalidLifecycleEventHookExecutionStatusException;
use AsyncAws\CodeDeploy\Exception\LifecycleEventAlreadyCompletedException;
use AsyncAws\CodeDeploy\Exception\UnsupportedActionForDeploymentTypeException;
use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\CodeDeploy\Result\PutLifecycleEventHookExecutionStatusOutput;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class CodeDeployClient extends AbstractApi
{
    /**
     * Sets the result of a Lambda validation function. The function validates lifecycle hooks during a deployment that uses
     * the AWS Lambda or Amazon ECS compute platform. For AWS Lambda deployments, the available lifecycle hooks are
     * `BeforeAllowTraffic` and `AfterAllowTraffic`. For Amazon ECS deployments, the available lifecycle hooks are
     * `BeforeInstall`, `AfterInstall`, `AfterAllowTestTraffic`, `BeforeAllowTraffic`, and `AfterAllowTraffic`. Lambda
     * validation functions return `Succeeded` or `Failed`. For more information, see AppSpec 'hooks' Section for an AWS
     * Lambda Deployment  and AppSpec 'hooks' Section for an Amazon ECS Deployment.
     *
     * @see https://docs.aws.amazon.com/codedeploy/latest/userguide/reference-appspec-file-structure-hooks.html#appspec-hooks-lambda
     * @see https://docs.aws.amazon.com/codedeploy/latest/userguide/reference-appspec-file-structure-hooks.html#appspec-hooks-ecs
     * @see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_PutLifecycleEventHookExecutionStatus.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codedeploy-2014-10-06.html#putlifecycleeventhookexecutionstatus
     *
     * @param array{
     *   deploymentId?: string,
     *   lifecycleEventHookExecutionId?: string,
     *   status?: LifecycleEventStatus::*,
     *   @region?: string,
     * }|PutLifecycleEventHookExecutionStatusInput $input
     *
     * @throws InvalidLifecycleEventHookExecutionStatusException
     * @throws InvalidLifecycleEventHookExecutionIdException
     * @throws LifecycleEventAlreadyCompletedException
     * @throws DeploymentIdRequiredException
     * @throws DeploymentDoesNotExistException
     * @throws InvalidDeploymentIdException
     * @throws UnsupportedActionForDeploymentTypeException
     */
    public function putLifecycleEventHookExecutionStatus($input = []): PutLifecycleEventHookExecutionStatusOutput
    {
        $input = PutLifecycleEventHookExecutionStatusInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutLifecycleEventHookExecutionStatus', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'InvalidLifecycleEventHookExecutionStatusException' => InvalidLifecycleEventHookExecutionStatusException::class,
            'InvalidLifecycleEventHookExecutionIdException' => InvalidLifecycleEventHookExecutionIdException::class,
            'LifecycleEventAlreadyCompletedException' => LifecycleEventAlreadyCompletedException::class,
            'DeploymentIdRequiredException' => DeploymentIdRequiredException::class,
            'DeploymentDoesNotExistException' => DeploymentDoesNotExistException::class,
            'InvalidDeploymentIdException' => InvalidDeploymentIdException::class,
            'UnsupportedActionForDeploymentTypeException' => UnsupportedActionForDeploymentTypeException::class,
        ]]));

        return new PutLifecycleEventHookExecutionStatusOutput($response);
    }

    protected function getAwsErrorFactory(): AwsErrorFactoryInterface
    {
        return new JsonRpcAwsErrorFactory();
    }

    protected function getEndpointMetadata(?string $region): array
    {
        if (null === $region) {
            $region = Configuration::DEFAULT_REGION;
        }

        switch ($region) {
            case 'cn-north-1':
            case 'cn-northwest-1':
                return [
                    'endpoint' => "https://codedeploy.$region.amazonaws.com.cn",
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://codedeploy.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-isob-east-1':
                return [
                    'endpoint' => "https://codedeploy.$region.sc2s.sgov.gov",
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1':
                return [
                    'endpoint' => 'https://codedeploy.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-east-1.amazonaws.com',
                    'signRegion' => 'us-east-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2':
                return [
                    'endpoint' => 'https://codedeploy.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1':
                return [
                    'endpoint' => 'https://codedeploy.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-east-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-gov-east-1.amazonaws.com',
                    'signRegion' => 'us-gov-east-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1':
                return [
                    'endpoint' => 'https://codedeploy.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1':
                return [
                    'endpoint' => 'https://codedeploy.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-west-1.amazonaws.com',
                    'signRegion' => 'us-west-1',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2':
                return [
                    'endpoint' => 'https://codedeploy.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-west-2-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-west-2.amazonaws.com',
                    'signRegion' => 'us-west-2',
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
        }

        return [
            'endpoint' => "https://codedeploy.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'codedeploy',
            'signVersions' => ['v4'],
        ];
    }
}
