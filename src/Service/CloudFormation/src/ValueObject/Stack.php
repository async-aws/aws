<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\Capability;
use AsyncAws\CloudFormation\Enum\DeletionMode;
use AsyncAws\CloudFormation\Enum\DetailedStatus;
use AsyncAws\CloudFormation\Enum\StackStatus;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The Stack data type.
 */
final class Stack
{
    /**
     * Unique identifier of the stack.
     *
     * @var string|null
     */
    private $stackId;

    /**
     * The name associated with the stack.
     *
     * @var string
     */
    private $stackName;

    /**
     * The unique ID of the change set.
     *
     * @var string|null
     */
    private $changeSetId;

    /**
     * A user-defined description associated with the stack.
     *
     * @var string|null
     */
    private $description;

    /**
     * A list of `Parameter` structures.
     *
     * @var Parameter[]|null
     */
    private $parameters;

    /**
     * The time at which the stack was created.
     *
     * @var \DateTimeImmutable
     */
    private $creationTime;

    /**
     * The time the stack was deleted.
     *
     * @var \DateTimeImmutable|null
     */
    private $deletionTime;

    /**
     * The time the stack was last updated. This field will only be returned if the stack has been updated at least once.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastUpdatedTime;

    /**
     * The rollback triggers for CloudFormation to monitor during stack creation and updating operations, and for the
     * specified monitoring period afterwards.
     *
     * @var RollbackConfiguration|null
     */
    private $rollbackConfiguration;

    /**
     * Current status of the stack.
     *
     * @var StackStatus::*
     */
    private $stackStatus;

    /**
     * Success/failure message associated with the stack status.
     *
     * @var string|null
     */
    private $stackStatusReason;

    /**
     * Boolean to enable or disable rollback on stack creation failures:
     *
     * - `true`: disable rollback.
     * - `false`: enable rollback.
     *
     * @var bool|null
     */
    private $disableRollback;

    /**
     * Amazon SNS topic Amazon Resource Names (ARNs) to which stack related events are published.
     *
     * @var string[]|null
     */
    private $notificationArns;

    /**
     * The amount of time within which stack creation should complete.
     *
     * @var int|null
     */
    private $timeoutInMinutes;

    /**
     * The capabilities allowed in the stack.
     *
     * @var list<Capability::*>|null
     */
    private $capabilities;

    /**
     * A list of output structures.
     *
     * @var Output[]|null
     */
    private $outputs;

    /**
     * The Amazon Resource Name (ARN) of an IAM role that's associated with the stack. During a stack operation,
     * CloudFormation uses this role's credentials to make calls on your behalf.
     *
     * @var string|null
     */
    private $roleArn;

    /**
     * A list of `Tag`s that specify information about the stack.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * Whether termination protection is enabled for the stack.
     *
     * For nested stacks [^1], termination protection is set on the root stack and can't be changed directly on the nested
     * stack. For more information, see Protect a CloudFormation stack from being deleted [^2] in the *CloudFormation User
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-nested-stacks.html
     * [^2]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-protect-stacks.html
     *
     * @var bool|null
     */
    private $enableTerminationProtection;

    /**
     * For nested stacks, the stack ID of the direct parent of this stack. For the first level of nested stacks, the root
     * stack is also the parent stack.
     *
     * For more information, see Nested stacks [^1] in the *CloudFormation User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-nested-stacks.html
     *
     * @var string|null
     */
    private $parentId;

    /**
     * For nested stacks, the stack ID of the top-level stack to which the nested stack ultimately belongs.
     *
     * For more information, see Nested stacks [^1] in the *CloudFormation User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-nested-stacks.html
     *
     * @var string|null
     */
    private $rootId;

    /**
     * Information about whether a stack's actual configuration differs, or has *drifted*, from its expected configuration,
     * as defined in the stack template and any values specified as template parameters. For more information, see Detect
     * unmanaged configuration changes to stacks and resources with drift detection [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-stack-drift.html
     *
     * @var StackDriftInformation|null
     */
    private $driftInformation;

    /**
     * When set to `true`, newly created resources are deleted when the operation rolls back. This includes newly created
     * resources marked with a deletion policy of `Retain`.
     *
     * Default: `false`
     *
     * @var bool|null
     */
    private $retainExceptOnCreate;

    /**
     * Specifies the deletion mode for the stack. Possible values are:
     *
     * - `STANDARD` - Use the standard behavior. Specifying this value is the same as not specifying this parameter.
     * - `FORCE_DELETE_STACK` - Delete the stack if it's stuck in a `DELETE_FAILED` state due to resource deletion failure.
     *
     * @var DeletionMode::*|null
     */
    private $deletionMode;

    /**
     * The detailed status of the resource or stack. If `CONFIGURATION_COMPLETE` is present, the resource or resource
     * configuration phase has completed and the stabilization of the resources is in progress. The stack sets
     * `CONFIGURATION_COMPLETE` when all of the resources in the stack have reached that event. For more information, see
     * Understand CloudFormation stack creation events [^1] in the *CloudFormation User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/stack-resource-configuration-complete.html
     *
     * @var DetailedStatus::*|null
     */
    private $detailedStatus;

    /**
     * @param array{
     *   StackId?: null|string,
     *   StackName: string,
     *   ChangeSetId?: null|string,
     *   Description?: null|string,
     *   Parameters?: null|array<Parameter|array>,
     *   CreationTime: \DateTimeImmutable,
     *   DeletionTime?: null|\DateTimeImmutable,
     *   LastUpdatedTime?: null|\DateTimeImmutable,
     *   RollbackConfiguration?: null|RollbackConfiguration|array,
     *   StackStatus: StackStatus::*,
     *   StackStatusReason?: null|string,
     *   DisableRollback?: null|bool,
     *   NotificationARNs?: null|string[],
     *   TimeoutInMinutes?: null|int,
     *   Capabilities?: null|array<Capability::*>,
     *   Outputs?: null|array<Output|array>,
     *   RoleARN?: null|string,
     *   Tags?: null|array<Tag|array>,
     *   EnableTerminationProtection?: null|bool,
     *   ParentId?: null|string,
     *   RootId?: null|string,
     *   DriftInformation?: null|StackDriftInformation|array,
     *   RetainExceptOnCreate?: null|bool,
     *   DeletionMode?: null|DeletionMode::*,
     *   DetailedStatus?: null|DetailedStatus::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stackId = $input['StackId'] ?? null;
        $this->stackName = $input['StackName'] ?? $this->throwException(new InvalidArgument('Missing required field "StackName".'));
        $this->changeSetId = $input['ChangeSetId'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->parameters = isset($input['Parameters']) ? array_map([Parameter::class, 'create'], $input['Parameters']) : null;
        $this->creationTime = $input['CreationTime'] ?? $this->throwException(new InvalidArgument('Missing required field "CreationTime".'));
        $this->deletionTime = $input['DeletionTime'] ?? null;
        $this->lastUpdatedTime = $input['LastUpdatedTime'] ?? null;
        $this->rollbackConfiguration = isset($input['RollbackConfiguration']) ? RollbackConfiguration::create($input['RollbackConfiguration']) : null;
        $this->stackStatus = $input['StackStatus'] ?? $this->throwException(new InvalidArgument('Missing required field "StackStatus".'));
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
        $this->retainExceptOnCreate = $input['RetainExceptOnCreate'] ?? null;
        $this->deletionMode = $input['DeletionMode'] ?? null;
        $this->detailedStatus = $input['DetailedStatus'] ?? null;
    }

    /**
     * @param array{
     *   StackId?: null|string,
     *   StackName: string,
     *   ChangeSetId?: null|string,
     *   Description?: null|string,
     *   Parameters?: null|array<Parameter|array>,
     *   CreationTime: \DateTimeImmutable,
     *   DeletionTime?: null|\DateTimeImmutable,
     *   LastUpdatedTime?: null|\DateTimeImmutable,
     *   RollbackConfiguration?: null|RollbackConfiguration|array,
     *   StackStatus: StackStatus::*,
     *   StackStatusReason?: null|string,
     *   DisableRollback?: null|bool,
     *   NotificationARNs?: null|string[],
     *   TimeoutInMinutes?: null|int,
     *   Capabilities?: null|array<Capability::*>,
     *   Outputs?: null|array<Output|array>,
     *   RoleARN?: null|string,
     *   Tags?: null|array<Tag|array>,
     *   EnableTerminationProtection?: null|bool,
     *   ParentId?: null|string,
     *   RootId?: null|string,
     *   DriftInformation?: null|StackDriftInformation|array,
     *   RetainExceptOnCreate?: null|bool,
     *   DeletionMode?: null|DeletionMode::*,
     *   DetailedStatus?: null|DetailedStatus::*,
     * }|Stack $input
     */
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

    /**
     * @return DeletionMode::*|null
     */
    public function getDeletionMode(): ?string
    {
        return $this->deletionMode;
    }

    public function getDeletionTime(): ?\DateTimeImmutable
    {
        return $this->deletionTime;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return DetailedStatus::*|null
     */
    public function getDetailedStatus(): ?string
    {
        return $this->detailedStatus;
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

    public function getRetainExceptOnCreate(): ?bool
    {
        return $this->retainExceptOnCreate;
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
