<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\ComputePlatform;
use AsyncAws\CodeDeploy\Enum\DeploymentCreator;
use AsyncAws\CodeDeploy\Enum\DeploymentStatus;
use AsyncAws\CodeDeploy\Enum\FileExistsBehavior;

/**
 * Information about the deployment.
 */
final class DeploymentInfo
{
    /**
     * The application name.
     */
    private $applicationName;

    /**
     * The deployment group name.
     */
    private $deploymentGroupName;

    /**
     * The deployment configuration name.
     */
    private $deploymentConfigName;

    /**
     * The unique ID of a deployment.
     */
    private $deploymentId;

    /**
     * Information about the application revision that was deployed to the deployment group before the most recent
     * successful deployment.
     */
    private $previousRevision;

    /**
     * Information about the location of stored application artifacts and the service from which to retrieve them.
     */
    private $revision;

    /**
     * The current state of the deployment as a whole.
     */
    private $status;

    /**
     * Information about any error associated with this deployment.
     */
    private $errorInformation;

    /**
     * A timestamp that indicates when the deployment was created.
     */
    private $createTime;

    /**
     * A timestamp that indicates when the deployment was deployed to the deployment group.
     */
    private $startTime;

    /**
     * A timestamp that indicates when the deployment was complete.
     */
    private $completeTime;

    /**
     * A summary of the deployment status of the instances in the deployment.
     */
    private $deploymentOverview;

    /**
     * A comment about the deployment.
     */
    private $description;

    /**
     * The means by which the deployment was created:.
     */
    private $creator;

    /**
     * If true, then if an `ApplicationStop`, `BeforeBlockTraffic`, or `AfterBlockTraffic` deployment lifecycle event to an
     * instance fails, then the deployment continues to the next deployment lifecycle event. For example, if
     * `ApplicationStop` fails, the deployment continues with DownloadBundle. If `BeforeBlockTraffic` fails, the deployment
     * continues with `BlockTraffic`. If `AfterBlockTraffic` fails, the deployment continues with `ApplicationStop`.
     */
    private $ignoreApplicationStopFailures;

    /**
     * Information about the automatic rollback configuration associated with the deployment.
     */
    private $autoRollbackConfiguration;

    /**
     * Indicates whether only instances that are not running the latest application revision are to be deployed to.
     */
    private $updateOutdatedInstancesOnly;

    /**
     * Information about a deployment rollback.
     */
    private $rollbackInfo;

    /**
     * Information about the type of deployment, either in-place or blue/green, you want to run and whether to route
     * deployment traffic behind a load balancer.
     */
    private $deploymentStyle;

    /**
     * Information about the instances that belong to the replacement environment in a blue/green deployment.
     */
    private $targetInstances;

    /**
     * Indicates whether the wait period set for the termination of instances in the original environment has started.
     * Status is 'false' if the KEEP_ALIVE option is specified. Otherwise, 'true' as soon as the termination wait period
     * starts.
     */
    private $instanceTerminationWaitTimeStarted;

    /**
     * Information about blue/green deployment options for this deployment.
     */
    private $blueGreenDeploymentConfiguration;

    /**
     * Information about the load balancer used in the deployment.
     */
    private $loadBalancerInfo;

    /**
     * Provides information about the results of a deployment, such as whether instances in the original environment in a
     * blue/green deployment were not terminated.
     */
    private $additionalDeploymentStatusInfo;

    /**
     * Information about how AWS CodeDeploy handles files that already exist in a deployment target location but weren't
     * part of the previous successful deployment.
     */
    private $fileExistsBehavior;

    /**
     * Messages that contain information about the status of a deployment.
     */
    private $deploymentStatusMessages;

    /**
     * The destination platform type for the deployment (`Lambda`, `Server`, or `ECS`).
     */
    private $computePlatform;

    /**
     * The unique ID for an external resource (for example, a CloudFormation stack ID) that is linked to this deployment.
     */
    private $externalId;

    private $relatedDeployments;

    /**
     * @param array{
     *   applicationName?: null|string,
     *   deploymentGroupName?: null|string,
     *   deploymentConfigName?: null|string,
     *   deploymentId?: null|string,
     *   previousRevision?: null|RevisionLocation|array,
     *   revision?: null|RevisionLocation|array,
     *   status?: null|DeploymentStatus::*,
     *   errorInformation?: null|ErrorInformation|array,
     *   createTime?: null|\DateTimeImmutable,
     *   startTime?: null|\DateTimeImmutable,
     *   completeTime?: null|\DateTimeImmutable,
     *   deploymentOverview?: null|DeploymentOverview|array,
     *   description?: null|string,
     *   creator?: null|DeploymentCreator::*,
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
     *   fileExistsBehavior?: null|FileExistsBehavior::*,
     *   deploymentStatusMessages?: null|string[],
     *   computePlatform?: null|ComputePlatform::*,
     *   externalId?: null|string,
     *   relatedDeployments?: null|RelatedDeployments|array,
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
    }

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
     * @return ComputePlatform::*|null
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
     * @return DeploymentCreator::*|null
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
     * @return FileExistsBehavior::*|null
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
     * @return DeploymentStatus::*|null
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
