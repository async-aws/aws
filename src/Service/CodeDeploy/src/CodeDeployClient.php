<?php

namespace AsyncAws\CodeDeploy;

use AsyncAws\CodeDeploy\Enum\FileExistsBehavior;
use AsyncAws\CodeDeploy\Enum\LifecycleEventStatus;
use AsyncAws\CodeDeploy\Exception\ApplicationDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\ApplicationNameRequiredException;
use AsyncAws\CodeDeploy\Exception\DeploymentConfigDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\DeploymentDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\DeploymentGroupDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\DeploymentGroupNameRequiredException;
use AsyncAws\CodeDeploy\Exception\DeploymentIdRequiredException;
use AsyncAws\CodeDeploy\Exception\DeploymentLimitExceededException;
use AsyncAws\CodeDeploy\Exception\DescriptionTooLongException;
use AsyncAws\CodeDeploy\Exception\InvalidApplicationNameException;
use AsyncAws\CodeDeploy\Exception\InvalidAutoRollbackConfigException;
use AsyncAws\CodeDeploy\Exception\InvalidAutoScalingGroupException;
use AsyncAws\CodeDeploy\Exception\InvalidDeploymentConfigNameException;
use AsyncAws\CodeDeploy\Exception\InvalidDeploymentGroupNameException;
use AsyncAws\CodeDeploy\Exception\InvalidDeploymentIdException;
use AsyncAws\CodeDeploy\Exception\InvalidFileExistsBehaviorException;
use AsyncAws\CodeDeploy\Exception\InvalidGitHubAccountTokenException;
use AsyncAws\CodeDeploy\Exception\InvalidIgnoreApplicationStopFailuresValueException;
use AsyncAws\CodeDeploy\Exception\InvalidLifecycleEventHookExecutionIdException;
use AsyncAws\CodeDeploy\Exception\InvalidLifecycleEventHookExecutionStatusException;
use AsyncAws\CodeDeploy\Exception\InvalidLoadBalancerInfoException;
use AsyncAws\CodeDeploy\Exception\InvalidRevisionException;
use AsyncAws\CodeDeploy\Exception\InvalidRoleException;
use AsyncAws\CodeDeploy\Exception\InvalidTargetInstancesException;
use AsyncAws\CodeDeploy\Exception\InvalidTrafficRoutingConfigurationException;
use AsyncAws\CodeDeploy\Exception\InvalidUpdateOutdatedInstancesOnlyValueException;
use AsyncAws\CodeDeploy\Exception\LifecycleEventAlreadyCompletedException;
use AsyncAws\CodeDeploy\Exception\RevisionDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\RevisionRequiredException;
use AsyncAws\CodeDeploy\Exception\ThrottlingException;
use AsyncAws\CodeDeploy\Exception\UnsupportedActionForDeploymentTypeException;
use AsyncAws\CodeDeploy\Input\CreateDeploymentInput;
use AsyncAws\CodeDeploy\Input\GetDeploymentInput;
use AsyncAws\CodeDeploy\Input\PutLifecycleEventHookExecutionStatusInput;
use AsyncAws\CodeDeploy\Result\CreateDeploymentOutput;
use AsyncAws\CodeDeploy\Result\GetDeploymentOutput;
use AsyncAws\CodeDeploy\Result\PutLifecycleEventHookExecutionStatusOutput;
use AsyncAws\CodeDeploy\ValueObject\AutoRollbackConfiguration;
use AsyncAws\CodeDeploy\ValueObject\RevisionLocation;
use AsyncAws\CodeDeploy\ValueObject\TargetInstances;
use AsyncAws\Core\AbstractApi;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\RequestContext;

class CodeDeployClient extends AbstractApi
{
    /**
     * Deploys an application revision through the specified deployment group.
     *
     * @see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_CreateDeployment.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codedeploy-2014-10-06.html#createdeployment
     *
     * @param array{
     *   applicationName: string,
     *   deploymentGroupName?: string,
     *   revision?: RevisionLocation|array,
     *   deploymentConfigName?: string,
     *   description?: string,
     *   ignoreApplicationStopFailures?: bool,
     *   targetInstances?: TargetInstances|array,
     *   autoRollbackConfiguration?: AutoRollbackConfiguration|array,
     *   updateOutdatedInstancesOnly?: bool,
     *   fileExistsBehavior?: FileExistsBehavior::*,
     *   @region?: string,
     * }|CreateDeploymentInput $input
     *
     * @throws ApplicationNameRequiredException
     * @throws InvalidApplicationNameException
     * @throws ApplicationDoesNotExistException
     * @throws DeploymentGroupNameRequiredException
     * @throws InvalidDeploymentGroupNameException
     * @throws DeploymentGroupDoesNotExistException
     * @throws RevisionRequiredException
     * @throws RevisionDoesNotExistException
     * @throws InvalidRevisionException
     * @throws InvalidDeploymentConfigNameException
     * @throws DeploymentConfigDoesNotExistException
     * @throws DescriptionTooLongException
     * @throws DeploymentLimitExceededException
     * @throws InvalidTargetInstancesException
     * @throws InvalidAutoRollbackConfigException
     * @throws InvalidLoadBalancerInfoException
     * @throws InvalidFileExistsBehaviorException
     * @throws InvalidRoleException
     * @throws InvalidAutoScalingGroupException
     * @throws ThrottlingException
     * @throws InvalidUpdateOutdatedInstancesOnlyValueException
     * @throws InvalidIgnoreApplicationStopFailuresValueException
     * @throws InvalidGitHubAccountTokenException
     * @throws InvalidTrafficRoutingConfigurationException
     */
    public function createDeployment($input): CreateDeploymentOutput
    {
        $input = CreateDeploymentInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateDeployment', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'ApplicationNameRequiredException' => ApplicationNameRequiredException::class,
            'InvalidApplicationNameException' => InvalidApplicationNameException::class,
            'ApplicationDoesNotExistException' => ApplicationDoesNotExistException::class,
            'DeploymentGroupNameRequiredException' => DeploymentGroupNameRequiredException::class,
            'InvalidDeploymentGroupNameException' => InvalidDeploymentGroupNameException::class,
            'DeploymentGroupDoesNotExistException' => DeploymentGroupDoesNotExistException::class,
            'RevisionRequiredException' => RevisionRequiredException::class,
            'RevisionDoesNotExistException' => RevisionDoesNotExistException::class,
            'InvalidRevisionException' => InvalidRevisionException::class,
            'InvalidDeploymentConfigNameException' => InvalidDeploymentConfigNameException::class,
            'DeploymentConfigDoesNotExistException' => DeploymentConfigDoesNotExistException::class,
            'DescriptionTooLongException' => DescriptionTooLongException::class,
            'DeploymentLimitExceededException' => DeploymentLimitExceededException::class,
            'InvalidTargetInstancesException' => InvalidTargetInstancesException::class,
            'InvalidAutoRollbackConfigException' => InvalidAutoRollbackConfigException::class,
            'InvalidLoadBalancerInfoException' => InvalidLoadBalancerInfoException::class,
            'InvalidFileExistsBehaviorException' => InvalidFileExistsBehaviorException::class,
            'InvalidRoleException' => InvalidRoleException::class,
            'InvalidAutoScalingGroupException' => InvalidAutoScalingGroupException::class,
            'ThrottlingException' => ThrottlingException::class,
            'InvalidUpdateOutdatedInstancesOnlyValueException' => InvalidUpdateOutdatedInstancesOnlyValueException::class,
            'InvalidIgnoreApplicationStopFailuresValueException' => InvalidIgnoreApplicationStopFailuresValueException::class,
            'InvalidGitHubAccountTokenException' => InvalidGitHubAccountTokenException::class,
            'InvalidTrafficRoutingConfigurationException' => InvalidTrafficRoutingConfigurationException::class,
        ]]));

        return new CreateDeploymentOutput($response);
    }

    /**
     * Gets information about a deployment.
     *
     * @see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_GetDeployment.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codedeploy-2014-10-06.html#getdeployment
     *
     * @param array{
     *   deploymentId: string,
     *   @region?: string,
     * }|GetDeploymentInput $input
     *
     * @throws DeploymentIdRequiredException
     * @throws InvalidDeploymentIdException
     * @throws DeploymentDoesNotExistException
     */
    public function getDeployment($input): GetDeploymentOutput
    {
        $input = GetDeploymentInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetDeployment', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DeploymentIdRequiredException' => DeploymentIdRequiredException::class,
            'InvalidDeploymentIdException' => InvalidDeploymentIdException::class,
            'DeploymentDoesNotExistException' => DeploymentDoesNotExistException::class,
        ]]));

        return new GetDeploymentOutput($response);
    }

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
                    'endpoint' => 'https://codedeploy.us-isob-east-1.sc2s.sgov.gov',
                    'signRegion' => 'us-isob-east-1',
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
            case 'us-east-2-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-east-2.amazonaws.com',
                    'signRegion' => 'us-east-2',
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
            case 'us-gov-west-1-fips':
                return [
                    'endpoint' => 'https://codedeploy-fips.us-gov-west-1.amazonaws.com',
                    'signRegion' => 'us-gov-west-1',
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
