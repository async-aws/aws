<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\Capability;
use AsyncAws\CloudFormation\Enum\StackStatus;

/**
 * The Stack data type.
 */
final class Stack
{
    /**
     * Unique identifier of the stack.
     */
    private $stackId;

    /**
     * The name associated with the stack.
     */
    private $stackName;

    /**
     * The unique ID of the change set.
     */
    private $changeSetId;

    /**
     * A user-defined description associated with the stack.
     */
    private $description;

    /**
     * A list of `Parameter` structures.
     */
    private $parameters;

    /**
     * The time at which the stack was created.
     */
    private $creationTime;

    /**
     * The time the stack was deleted.
     */
    private $deletionTime;

    /**
     * The time the stack was last updated. This field will only be returned if the stack has been updated at least once.
     */
    private $lastUpdatedTime;

    /**
     * The rollback triggers for CloudFormation to monitor during stack creation and updating operations, and for the
     * specified monitoring period afterwards.
     */
    private $rollbackConfiguration;

    /**
     * Current status of the stack.
     */
    private $stackStatus;

    /**
     * Success/failure message associated with the stack status.
     */
    private $stackStatusReason;

    /**
     * Boolean to enable or disable rollback on stack creation failures:.
     */
    private $disableRollback;

    /**
     * Amazon SNS topic Amazon Resource Names (ARNs) to which stack related events are published.
     */
    private $notificationArns;

    /**
     * The amount of time within which stack creation should complete.
     */
    private $timeoutInMinutes;

    /**
     * The capabilities allowed in the stack.
     */
    private $capabilities;

    /**
     * A list of output structures.
     */
    private $outputs;

    /**
     * The Amazon Resource Name (ARN) of an Identity and Access Management (IAM) role that's associated with the stack.
     * During a stack operation, CloudFormation uses this role's credentials to make calls on your behalf.
     */
    private $roleArn;

    /**
     * A list of `Tag`s that specify information about the stack.
     */
    private $tags;

    /**
     * Whether termination protection is enabled for the stack.
     */
    private $enableTerminationProtection;

    /**
     * For nested stacks--stacks created as resources for another stack--the stack ID of the direct parent of this stack.
     * For the first level of nested stacks, the root stack is also the parent stack.
     */
    private $parentId;

    /**
     * For nested stacks--stacks created as resources for another stack--the stack ID of the top-level stack to which the
     * nested stack ultimately belongs.
     */
    private $rootId;

    /**
     * Information on whether a stack's actual configuration differs, or has *drifted*, from it's expected configuration, as
     * defined in the stack template and any values specified as template parameters. For more information, see Detecting
     * Unregulated Configuration Changes to Stacks and Resources.
     *
     * @see http://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-stack-drift.html
     */
    private $driftInformation;

    /**
     * @param array{
     *   StackId?: null|string,
     *   StackName: string,
     *   ChangeSetId?: null|string,
     *   Description?: null|string,
     *   Parameters?: null|Parameter[],
     *   CreationTime: \DateTimeImmutable,
     *   DeletionTime?: null|\DateTimeImmutable,
     *   LastUpdatedTime?: null|\DateTimeImmutable,
     *   RollbackConfiguration?: null|RollbackConfiguration|array,
     *   StackStatus: StackStatus::*,
     *   StackStatusReason?: null|string,
     *   DisableRollback?: null|bool,
     *   NotificationARNs?: null|string[],
     *   TimeoutInMinutes?: null|int,
     *   Capabilities?: null|list<Capability::*>,
     *   Outputs?: null|Output[],
     *   RoleARN?: null|string,
     *   Tags?: null|Tag[],
     *   EnableTerminationProtection?: null|bool,
     *   ParentId?: null|string,
     *   RootId?: null|string,
     *   DriftInformation?: null|StackDriftInformation|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stackId = $input['StackId'] ?? null;
        $this->stackName = $input['StackName'] ?? null;
        $this->changeSetId = $input['ChangeSetId'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->parameters = isset($input['Parameters']) ? array_map([Parameter::class, 'create'], $input['Parameters']) : null;
        $this->creationTime = $input['CreationTime'] ?? null;
        $this->deletionTime = $input['DeletionTime'] ?? null;
        $this->lastUpdatedTime = $input['LastUpdatedTime'] ?? null;
        $this->rollbackConfiguration = isset($input['RollbackConfiguration']) ? RollbackConfiguration::create($input['RollbackConfiguration']) : null;
        $this->stackStatus = $input['StackStatus'] ?? null;
        $this->stackStatusReason = $input['StackStatusReason'] ?? null;
        $this->disableRollback = $input['DisableRollback'] ?? null;
        $this->notificationArns = $input['NotificationARNs'] ?? null;
        $this->timeoutInMinutes = $input['TimeoutInMinutes'] ?? null;
        $this->capabilities = $input['Capabilities'] ?? null;
        $this->outputs = isset($input['Outputs']) ? array_map([Output::class, 'create'], $input['Outputs']) : null;
        $this->roleArn = $input['RoleARN'] ?? null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->enableTerminationProtection = $input['EnableTerminationProtection'] ?? null;
        $this->parentId = $input['ParentId'] ?? null;
        $this->rootId = $input['RootId'] ?? null;
        $this->driftInformation = isset($input['DriftInformation']) ? StackDriftInformation::create($input['DriftInformation']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Capability::*>
     */
    public function getCapabilities(): array
    {
        return $this->capabilities ?? [];
    }

    public function getChangeSetId(): ?string
    {
        return $this->changeSetId;
    }

    public function getCreationTime(): \DateTimeImmutable
    {
        return $this->creationTime;
    }

    public function getDeletionTime(): ?\DateTimeImmutable
    {
        return $this->deletionTime;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDisableRollback(): ?bool
    {
        return $this->disableRollback;
    }

    public function getDriftInformation(): ?StackDriftInformation
    {
        return $this->driftInformation;
    }

    public function getEnableTerminationProtection(): ?bool
    {
        return $this->enableTerminationProtection;
    }

    public function getLastUpdatedTime(): ?\DateTimeImmutable
    {
        return $this->lastUpdatedTime;
    }

    /**
     * @return string[]
     */
    public function getNotificationArns(): array
    {
        return $this->notificationArns ?? [];
    }

    /**
     * @return Output[]
     */
    public function getOutputs(): array
    {
        return $this->outputs ?? [];
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    public function getParentId(): ?string
    {
        return $this->parentId;
    }

    public function getRoleArn(): ?string
    {
        return $this->roleArn;
    }

    public function getRollbackConfiguration(): ?RollbackConfiguration
    {
        return $this->rollbackConfiguration;
    }

    public function getRootId(): ?string
    {
        return $this->rootId;
    }

    public function getStackId(): ?string
    {
        return $this->stackId;
    }

    public function getStackName(): string
    {
        return $this->stackName;
    }

    /**
     * @return StackStatus::*
     */
    public function getStackStatus(): string
    {
        return $this->stackStatus;
    }

    public function getStackStatusReason(): ?string
    {
        return $this->stackStatusReason;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getTimeoutInMinutes(): ?int
    {
        return $this->timeoutInMinutes;
    }
}
