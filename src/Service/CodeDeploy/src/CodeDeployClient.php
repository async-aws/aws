<?php

namespace AsyncAws\CodeDeploy;

use AsyncAws\CodeDeploy\Enum\FileExistsBehavior;
use AsyncAws\CodeDeploy\Enum\LifecycleEventStatus;
use AsyncAws\CodeDeploy\Exception\AlarmsLimitExceededException;
use AsyncAws\CodeDeploy\Exception\ApplicationDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\ApplicationNameRequiredException;
use AsyncAws\CodeDeploy\Exception\DeploymentConfigDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\DeploymentDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\DeploymentGroupDoesNotExistException;
use AsyncAws\CodeDeploy\Exception\DeploymentGroupNameRequiredException;
use AsyncAws\CodeDeploy\Exception\DeploymentIdRequiredException;
use AsyncAws\CodeDeploy\Exception\DeploymentLimitExceededException;
use AsyncAws\CodeDeploy\Exception\DescriptionTooLongException;
use AsyncAws\CodeDeploy\Exception\InvalidAlarmConfigException;
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
use AsyncAws\CodeDeploy\ValueObject\AlarmConfiguration;
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
     *   deploymentGroupName?: string|null,
     *   revision?: RevisionLocation|array|null,
     *   deploymentConfigName?: string|null,
     *   description?: string|null,
     *   ignoreApplicationStopFailures?: bool|null,
     *   targetInstances?: TargetInstances|array|null,
     *   autoRollbackConfiguration?: AutoRollbackConfiguration|array|null,
     *   updateOutdatedInstancesOnly?: bool|null,
     *   fileExistsBehavior?: FileExistsBehavior::*|null,
     *   overrideAlarmConfiguration?: AlarmConfiguration|array|null,
     *   '@region'?: string|null,
     * }|CreateDeploymentInput $input
     *
     * @throws AlarmsLimitExceededException
     * @throws ApplicationDoesNotExistException
     * @throws ApplicationNameRequiredException
     * @throws DeploymentConfigDoesNotExistException
     * @throws DeploymentGroupDoesNotExistException
     * @throws DeploymentGroupNameRequiredException
     * @throws DeploymentLimitExceededException
     * @throws DescriptionTooLongException
     * @throws InvalidAlarmConfigException
     * @throws InvalidApplicationNameException
     * @throws InvalidAutoRollbackConfigException
     * @throws InvalidAutoScalingGroupException
     * @throws InvalidDeploymentConfigNameException
     * @throws InvalidDeploymentGroupNameException
     * @throws InvalidFileExistsBehaviorException
     * @throws InvalidGitHubAccountTokenException
     * @throws InvalidIgnoreApplicationStopFailuresValueException
     * @throws InvalidLoadBalancerInfoException
     * @throws InvalidRevisionException
     * @throws InvalidRoleException
     * @throws InvalidTargetInstancesException
     * @throws InvalidTrafficRoutingConfigurationException
     * @throws InvalidUpdateOutdatedInstancesOnlyValueException
     * @throws RevisionDoesNotExistException
     * @throws RevisionRequiredException
     * @throws ThrottlingException
     */
    public function createDeployment($input): CreateDeploymentOutput
    {
        $input = CreateDeploymentInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'CreateDeployment', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'AlarmsLimitExceededException' => AlarmsLimitExceededException::class,
            'ApplicationDoesNotExistException' => ApplicationDoesNotExistException::class,
            'ApplicationNameRequiredException' => ApplicationNameRequiredException::class,
            'DeploymentConfigDoesNotExistException' => DeploymentConfigDoesNotExistException::class,
            'DeploymentGroupDoesNotExistException' => DeploymentGroupDoesNotExistException::class,
            'DeploymentGroupNameRequiredException' => DeploymentGroupNameRequiredException::class,
            'DeploymentLimitExceededException' => DeploymentLimitExceededException::class,
            'DescriptionTooLongException' => DescriptionTooLongException::class,
            'InvalidAlarmConfigException' => InvalidAlarmConfigException::class,
            'InvalidApplicationNameException' => InvalidApplicationNameException::class,
            'InvalidAutoRollbackConfigException' => InvalidAutoRollbackConfigException::class,
            'InvalidAutoScalingGroupException' => InvalidAutoScalingGroupException::class,
            'InvalidDeploymentConfigNameException' => InvalidDeploymentConfigNameException::class,
            'InvalidDeploymentGroupNameException' => InvalidDeploymentGroupNameException::class,
            'InvalidFileExistsBehaviorException' => InvalidFileExistsBehaviorException::class,
            'InvalidGitHubAccountTokenException' => InvalidGitHubAccountTokenException::class,
            'InvalidIgnoreApplicationStopFailuresValueException' => InvalidIgnoreApplicationStopFailuresValueException::class,
            'InvalidLoadBalancerInfoException' => InvalidLoadBalancerInfoException::class,
            'InvalidRevisionException' => InvalidRevisionException::class,
            'InvalidRoleException' => InvalidRoleException::class,
            'InvalidTargetInstancesException' => InvalidTargetInstancesException::class,
            'InvalidTrafficRoutingConfigurationException' => InvalidTrafficRoutingConfigurationException::class,
            'InvalidUpdateOutdatedInstancesOnlyValueException' => InvalidUpdateOutdatedInstancesOnlyValueException::class,
            'RevisionDoesNotExistException' => RevisionDoesNotExistException::class,
            'RevisionRequiredException' => RevisionRequiredException::class,
            'ThrottlingException' => ThrottlingException::class,
        ]]));

        return new CreateDeploymentOutput($response);
    }

    /**
     * Gets information about a deployment.
     *
     * > The `content` property of the `appSpecContent` object in the returned revision is always null. Use
     * > `GetApplicationRevision` and the `sha256` property of the returned `appSpecContent` object to get the content of
     * > the deployment’s AppSpec file.
     *
     * @see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_GetDeployment.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codedeploy-2014-10-06.html#getdeployment
     *
     * @param array{
     *   deploymentId: string,
     *   '@region'?: string|null,
     * }|GetDeploymentInput $input
     *
     * @throws DeploymentDoesNotExistException
     * @throws DeploymentIdRequiredException
     * @throws InvalidDeploymentIdException
     */
    public function getDeployment($input): GetDeploymentOutput
    {
        $input = GetDeploymentInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'GetDeployment', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DeploymentDoesNotExistException' => DeploymentDoesNotExistException::class,
            'DeploymentIdRequiredException' => DeploymentIdRequiredException::class,
            'InvalidDeploymentIdException' => InvalidDeploymentIdException::class,
        ]]));

        return new GetDeploymentOutput($response);
    }

    /**
     * Sets the result of a Lambda validation function. The function validates lifecycle hooks during a deployment that uses
     * the Lambda or Amazon ECS compute platform. For Lambda deployments, the available lifecycle hooks are
     * `BeforeAllowTraffic` and `AfterAllowTraffic`. For Amazon ECS deployments, the available lifecycle hooks are
     * `BeforeInstall`, `AfterInstall`, `AfterAllowTestTraffic`, `BeforeAllowTraffic`, and `AfterAllowTraffic`. Lambda
     * validation functions return `Succeeded` or `Failed`. For more information, see AppSpec 'hooks' Section for an Lambda
     * Deployment [^1] and AppSpec 'hooks' Section for an Amazon ECS Deployment [^2].
     *
     * [^1]: https://docs.aws.amazon.com/codedeploy/latest/userguide/reference-appspec-file-structure-hooks.html#appspec-hooks-lambda
     * [^2]: https://docs.aws.amazon.com/codedeploy/latest/userguide/reference-appspec-file-structure-hooks.html#appspec-hooks-ecs
     *
     * @see https://docs.aws.amazon.com/codedeploy/latest/APIReference/API_PutLifecycleEventHookExecutionStatus.html
     * @see https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-codedeploy-2014-10-06.html#putlifecycleeventhookexecutionstatus
     *
     * @param array{
     *   deploymentId?: string|null,
     *   lifecycleEventHookExecutionId?: string|null,
     *   status?: LifecycleEventStatus::*|null,
     *   '@region'?: string|null,
     * }|PutLifecycleEventHookExecutionStatusInput $input
     *
     * @throws DeploymentDoesNotExistException
     * @throws DeploymentIdRequiredException
     * @throws InvalidDeploymentIdException
     * @throws InvalidLifecycleEventHookExecutionIdException
     * @throws InvalidLifecycleEventHookExecutionStatusException
     * @throws LifecycleEventAlreadyCompletedException
     * @throws UnsupportedActionForDeploymentTypeException
     */
    public function putLifecycleEventHookExecutionStatus($input = []): PutLifecycleEventHookExecutionStatusOutput
    {
        $input = PutLifecycleEventHookExecutionStatusInput::create($input);
        $response = $this->getResponse($input->request(), new RequestContext(['operation' => 'PutLifecycleEventHookExecutionStatus', 'region' => $input->getRegion(), 'exceptionMapping' => [
            'DeploymentDoesNotExistException' => DeploymentDoesNotExistException::class,
            'DeploymentIdRequiredException' => DeploymentIdRequiredException::class,
            'InvalidDeploymentIdException' => InvalidDeploymentIdException::class,
            'InvalidLifecycleEventHookExecutionIdException' => InvalidLifecycleEventHookExecutionIdException::class,
            'InvalidLifecycleEventHookExecutionStatusException' => InvalidLifecycleEventHookExecutionStatusException::class,
            'LifecycleEventAlreadyCompletedException' => LifecycleEventAlreadyCompletedException::class,
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
            case 'us-iso-east-1':
            case 'us-iso-west-1':
                return [
                    'endpoint' => "https://codedeploy.$region.c2s.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'us-isof-east-1':
            case 'us-isof-south-1':
                return [
                    'endpoint' => "https://codedeploy.$region.csp.hci.ic.gov",
                    'signRegion' => $region,
                    'signService' => 'codedeploy',
                    'signVersions' => ['v4'],
                ];
            case 'eu-isoe-west-1':
                return [
                    'endpoint' => 'https://codedeploy.eu-isoe-west-1.cloud.adc-e.uk',
                    'signRegion' => 'eu-isoe-west-1',
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
        }

        return [
            'endpoint' => "https://codedeploy.$region.amazonaws.com",
            'signRegion' => $region,
            'signService' => 'codedeploy',
            'signVersions' => ['v4'],
        ];
    }
}
