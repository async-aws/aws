<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\ComputePlatform;
use AsyncAws\CodeDeploy\Enum\DeploymentCreator;
use AsyncAws\CodeDeploy\Enum\DeploymentStatus;
use AsyncAws\CodeDeploy\Enum\FileExistsBehavior;

/**
 * Information about a deployment.
 */
final class DeploymentInfo
{
    /**
     * The application name.
     *
     * @var string|null
     */
    private $applicationName;

    /**
     * The deployment group name.
     *
     * @var string|null
     */
    private $deploymentGroupName;

    /**
     * The deployment configuration name.
     *
     * @var string|null
     */
    private $deploymentConfigName;

    /**
     * The unique ID of a deployment.
     *
     * @var string|null
     */
    private $deploymentId;

    /**
     * Information about the application revision that was deployed to the deployment group before the most recent
     * successful deployment.
     *
     * @var RevisionLocation|null
     */
    private $previousRevision;

    /**
     * Information about the location of stored application artifacts and the service from which to retrieve them.
     *
     * @var RevisionLocation|null
     */
    private $revision;

    /**
     * The current state of the deployment as a whole.
     *
     * @var DeploymentStatus::*|string|null
     */
    private $status;

    /**
     * Information about any error associated with this deployment.
     *
     * @var ErrorInformation|null
     */
    private $errorInformation;

    /**
     * A timestamp that indicates when the deployment was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $createTime;

    /**
     * A timestamp that indicates when the deployment was deployed to the deployment group.
     *
     * In some cases, the reported value of the start time might be later than the complete time. This is due to differences
     * in the clock settings of backend servers that participate in the deployment process.
     *
     * @var \DateTimeImmutable|null
     */
    private $startTime;

    /**
     * A timestamp that indicates when the deployment was complete.
     *
     * @var \DateTimeImmutable|null
     */
    private $completeTime;

    /**
     * A summary of the deployment status of the instances in the deployment.
     *
     * @var DeploymentOverview|null
     */
    private $deploymentOverview;

    /**
     * A comment about the deployment.
     *
     * @var string|null
     */
    private $description;

    /**
     * The means by which the deployment was created:
     *
     * - `user`: A user created the deployment.
     * - `autoscaling`: Amazon EC2 Auto Scaling created the deployment.
     * - `codeDeployRollback`: A rollback process created the deployment.
     * - `CodeDeployAutoUpdate`: An auto-update process created the deployment when it detected outdated Amazon EC2
     *   instances.
     *
     * @var DeploymentCreator::*|string|null
     */
    private $creator;

    /**
     * If true, then if an `ApplicationStop`, `BeforeBlockTraffic`, or `AfterBlockTraffic` deployment lifecycle event to an
     * instance fails, then the deployment continues to the next deployment lifecycle event. For example, if
     * `ApplicationStop` fails, the deployment continues with DownloadBundle. If `BeforeBlockTraffic` fails, the deployment
     * continues with `BlockTraffic`. If `AfterBlockTraffic` fails, the deployment continues with `ApplicationStop`.
     *
     * If false or not specified, then if a lifecycle event fails during a deployment to an instance, that deployment fails.
     * If deployment to that instance is part of an overall deployment and the number of healthy hosts is not less than the
     * minimum number of healthy hosts, then a deployment to the next instance is attempted.
     *
     * During a deployment, the CodeDeploy agent runs the scripts specified for `ApplicationStop`, `BeforeBlockTraffic`, and
     * `AfterBlockTraffic` in the AppSpec file from the previous successful deployment. (All other scripts are run from the
     * AppSpec file in the current deployment.) If one of these scripts contains an error and does not run successfully, the
     * deployment can fail.
     *
     * If the cause of the failure is a script from the last successful deployment that will never run successfully, create
     * a new deployment and use `ignoreApplicationStopFailures` to specify that the `ApplicationStop`, `BeforeBlockTraffic`,
     * and `AfterBlockTraffic` failures should be ignored.
     *
     * @var bool|null
     */
    private $ignoreApplicationStopFailures;

    /**
     * Information about the automatic rollback configuration associated with the deployment.
     *
     * @var AutoRollbackConfiguration|null
     */
    private $autoRollbackConfiguration;

    /**
     * Indicates whether only instances that are not running the latest application revision are to be deployed to.
     *
     * @var bool|null
     */
    private $updateOutdatedInstancesOnly;

    /**
     * Information about a deployment rollback.
     *
     * @var RollbackInfo|null
     */
    private $rollbackInfo;

    /**
     * Information about the type of deployment, either in-place or blue/green, you want to run and whether to route
     * deployment traffic behind a load balancer.
     *
     * @var DeploymentStyle|null
     */
    private $deploymentStyle;

    /**
     * Information about the instances that belong to the replacement environment in a blue/green deployment.
     *
     * @var TargetInstances|null
     */
    private $targetInstances;

    /**
     * Indicates whether the wait period set for the termination of instances in the original environment has started.
     * Status is 'false' if the KEEP_ALIVE option is specified. Otherwise, 'true' as soon as the termination wait period
     * starts.
     *
     * @var bool|null
     */
    private $instanceTerminationWaitTimeStarted;

    /**
     * Information about blue/green deployment options for this deployment.
     *
     * @var BlueGreenDeploymentConfiguration|null
     */
    private $blueGreenDeploymentConfiguration;

    /**
     * Information about the load balancer used in the deployment.
     *
     * @var LoadBalancerInfo|null
     */
    private $loadBalancerInfo;

    /**
     * Provides information about the results of a deployment, such as whether instances in the original environment in a
     * blue/green deployment were not terminated.
     *
     * @var string|null
     */
    private $additionalDeploymentStatusInfo;

    /**
     * Information about how CodeDeploy handles files that already exist in a deployment target location but weren't part of
     * the previous successful deployment.
     *
     * - `DISALLOW`: The deployment fails. This is also the default behavior if no option is specified.
     * - `OVERWRITE`: The version of the file from the application revision currently being deployed replaces the version
     *   already on the instance.
     * - `RETAIN`: The version of the file already on the instance is kept and used as part of the new deployment.
     *
     * @var FileExistsBehavior::*|string|null
     */
    private $fileExistsBehavior;

    /**
     * Messages that contain information about the status of a deployment.
     *
     * @var string[]|null
     */
    private $deploymentStatusMessages;

    /**
     * The destination platform type for the deployment (`Lambda`, `Server`, or `ECS`).
     *
     * @var ComputePlatform::*|string|null
     */
    private $computePlatform;

    /**
     * The unique ID for an external resource (for example, a CloudFormation stack ID) that is linked to this deployment.
     *
     * @var string|null
     */
    private $externalId;

    /**
     * @var RelatedDeployments|null
     */
    private $relatedDeployments;

    /**
     * @var AlarmConfiguration|null
     */
    private $overrideAlarmConfiguration;

    /**
     * @param array{
     *   applicationName?: null|string,
     *   deploymentGroupName?: null|string,
     *   deploymentConfigName?: null|string,
     *   deploymentId?: null|string,
     *   previousRevision?: null|RevisionLocation|array,
     *   revision?: null|RevisionLocation|array,
     *   status?: null|DeploymentStatus::*|string,
     *   errorInformation?: null|ErrorInformation|array,
     *   createTime?: null|\DateTimeImmutable,
     *   startTime?: null|\DateTimeImmutable,
     *   completeTime?: null|\DateTimeImmutable,
     *   deploymentOverview?: null|DeploymentOverview|array,
     *   description?: null|string,
     *   creator?: null|DeploymentCreator::*|string,
     *   ignoreApplicationStopFailures?: null|bool,
     *   autoRollbackConfiguration?: null|AutoRollbackConfiguration|array,
     *   updateOutdatedInstancesOnly?: null|bool,
     *   rollbackInfo?: null|RollbackInfo|array,
     *   deploymentStyle?: null|DeploymentStyle|array,
     *   targetInstances?: null|TargetInstances|array,
     *   instanceTerminationWaitTimeStarted?: null|bool,
     *   blueGreenDeploymentConfiguration?: null|BlueGreenDeploymentConfiguration|array,
     *   loadBalancerInfo?: null|LoadBalancerInfo|array,
     *   additionalDeploymentStatusInfo?: null|string,
     *   fileExistsBehavior?: null|FileExistsBehavior::*|string,
     *   deploymentStatusMessages?: null|string[],
     *   computePlatform?: null|ComputePlatform::*|string,
     *   externalId?: null|string,
     *   relatedDeployments?: null|RelatedDeployments|array,
     *   overrideAlarmConfiguration?: null|AlarmConfiguration|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->applicationName = $input['applicationName'] ?? null;
        $this->deploymentGroupName = $input['deploymentGroupName'] ?? null;
        $this->deploymentConfigName = $input['deploymentConfigName'] ?? null;
        $this->deploymentId = $input['deploymentId'] ?? null;
        $this->previousRevision = isset($input['previousRevision']) ? RevisionLocation::create($input['previousRevision']) : null;
        $this->revision = isset($input['revision']) ? RevisionLocation::create($input['revision']) : null;
        $this->status = $input['status'] ?? null;
        $this->errorInformation = isset($input['errorInformation']) ? ErrorInformation::create($input['errorInformation']) : null;
        $this->createTime = $input['createTime'] ?? null;
        $this->startTime = $input['startTime'] ?? null;
        $this->completeTime = $input['completeTime'] ?? null;
        $this->deploymentOverview = isset($input['deploymentOverview']) ? DeploymentOverview::create($input['deploymentOverview']) : null;
        $this->description = $input['description'] ?? null;
        $this->creator = $input['creator'] ?? null;
        $this->ignoreApplicationStopFailures = $input['ignoreApplicationStopFailures'] ?? null;
        $this->autoRollbackConfiguration = isset($input['autoRollbackConfiguration']) ? AutoRollbackConfiguration::create($input['autoRollbackConfiguration']) : null;
        $this->updateOutdatedInstancesOnly = $input['updateOutdatedInstancesOnly'] ?? null;
        $this->rollbackInfo = isset($input['rollbackInfo']) ? RollbackInfo::create($input['rollbackInfo']) : null;
        $this->deploymentStyle = isset($input['deploymentStyle']) ? DeploymentStyle::create($input['deploymentStyle']) : null;
        $this->targetInstances = isset($input['targetInstances']) ? TargetInstances::create($input['targetInstances']) : null;
        $this->instanceTerminationWaitTimeStarted = $input['instanceTerminationWaitTimeStarted'] ?? null;
        $this->blueGreenDeploymentConfiguration = isset($input['blueGreenDeploymentConfiguration']) ? BlueGreenDeploymentConfiguration::create($input['blueGreenDeploymentConfiguration']) : null;
        $this->loadBalancerInfo = isset($input['loadBalancerInfo']) ? LoadBalancerInfo::create($input['loadBalancerInfo']) : null;
        $this->additionalDeploymentStatusInfo = $input['additionalDeploymentStatusInfo'] ?? null;
        $this->fileExistsBehavior = $input['fileExistsBehavior'] ?? null;
        $this->deploymentStatusMessages = $input['deploymentStatusMessages'] ?? null;
        $this->computePlatform = $input['computePlatform'] ?? null;
        $this->externalId = $input['externalId'] ?? null;
        $this->relatedDeployments = isset($input['relatedDeployments']) ? RelatedDeployments::create($input['relatedDeployments']) : null;
        $this->overrideAlarmConfiguration = isset($input['overrideAlarmConfiguration']) ? AlarmConfiguration::create($input['overrideAlarmConfiguration']) : null;
    }

    /**
     * @param array{
     *   applicationName?: null|string,
     *   deploymentGroupName?: null|string,
     *   deploymentConfigName?: null|string,
     *   deploymentId?: null|string,
     *   previousRevision?: null|RevisionLocation|array,
     *   revision?: null|RevisionLocation|array,
     *   status?: null|DeploymentStatus::*|string,
     *   errorInformation?: null|ErrorInformation|array,
     *   createTime?: null|\DateTimeImmutable,
     *   startTime?: null|\DateTimeImmutable,
     *   completeTime?: null|\DateTimeImmutable,
     *   deploymentOverview?: null|DeploymentOverview|array,
     *   description?: null|string,
     *   creator?: null|DeploymentCreator::*|string,
     *   ignoreApplicationStopFailures?: null|bool,
     *   autoRollbackConfiguration?: null|AutoRollbackConfiguration|array,
     *   updateOutdatedInstancesOnly?: null|bool,
     *   rollbackInfo?: null|RollbackInfo|array,
     *   deploymentStyle?: null|DeploymentStyle|array,
     *   targetInstances?: null|TargetInstances|array,
     *   instanceTerminationWaitTimeStarted?: null|bool,
     *   blueGreenDeploymentConfiguration?: null|BlueGreenDeploymentConfiguration|array,
     *   loadBalancerInfo?: null|LoadBalancerInfo|array,
     *   additionalDeploymentStatusInfo?: null|string,
     *   fileExistsBehavior?: null|FileExistsBehavior::*|string,
     *   deploymentStatusMessages?: null|string[],
     *   computePlatform?: null|ComputePlatform::*|string,
     *   externalId?: null|string,
     *   relatedDeployments?: null|RelatedDeployments|array,
     *   overrideAlarmConfiguration?: null|AlarmConfiguration|array,
     * }|DeploymentInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdditionalDeploymentStatusInfo(): ?string
    {
        return $this->additionalDeploymentStatusInfo;
    }

    public function getApplicationName(): ?string
    {
        return $this->applicationName;
    }

    public function getAutoRollbackConfiguration(): ?AutoRollbackConfiguration
    {
        return $this->autoRollbackConfiguration;
    }

    public function getBlueGreenDeploymentConfiguration(): ?BlueGreenDeploymentConfiguration
    {
        return $this->blueGreenDeploymentConfiguration;
    }

    public function getCompleteTime(): ?\DateTimeImmutable
    {
        return $this->completeTime;
    }

    /**
     * @return ComputePlatform::*|string|null
     */
    public function getComputePlatform(): ?string
    {
        return $this->computePlatform;
    }

    public function getCreateTime(): ?\DateTimeImmutable
    {
        return $this->createTime;
    }

    /**
     * @return DeploymentCreator::*|string|null
     */
    public function getCreator(): ?string
    {
        return $this->creator;
    }

    public function getDeploymentConfigName(): ?string
    {
        return $this->deploymentConfigName;
    }

    public function getDeploymentGroupName(): ?string
    {
        return $this->deploymentGroupName;
    }

    public function getDeploymentId(): ?string
    {
        return $this->deploymentId;
    }

    public function getDeploymentOverview(): ?DeploymentOverview
    {
        return $this->deploymentOverview;
    }

    /**
     * @return string[]
     */
    public function getDeploymentStatusMessages(): array
    {
        return $this->deploymentStatusMessages ?? [];
    }

    public function getDeploymentStyle(): ?DeploymentStyle
    {
        return $this->deploymentStyle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getErrorInformation(): ?ErrorInformation
    {
        return $this->errorInformation;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @return FileExistsBehavior::*|string|null
     */
    public function getFileExistsBehavior(): ?string
    {
        return $this->fileExistsBehavior;
    }

    public function getIgnoreApplicationStopFailures(): ?bool
    {
        return $this->ignoreApplicationStopFailures;
    }

    public function getInstanceTerminationWaitTimeStarted(): ?bool
    {
        return $this->instanceTerminationWaitTimeStarted;
    }

    public function getLoadBalancerInfo(): ?LoadBalancerInfo
    {
        return $this->loadBalancerInfo;
    }

    public function getOverrideAlarmConfiguration(): ?AlarmConfiguration
    {
        return $this->overrideAlarmConfiguration;
    }

    public function getPreviousRevision(): ?RevisionLocation
    {
        return $this->previousRevision;
    }

    public function getRelatedDeployments(): ?RelatedDeployments
    {
        return $this->relatedDeployments;
    }

    public function getRevision(): ?RevisionLocation
    {
        return $this->revision;
    }

    public function getRollbackInfo(): ?RollbackInfo
    {
        return $this->rollbackInfo;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * @return DeploymentStatus::*|string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getTargetInstances(): ?TargetInstances
    {
        return $this->targetInstances;
    }

    public function getUpdateOutdatedInstancesOnly(): ?bool
    {
        return $this->updateOutdatedInstancesOnly;
    }
}
