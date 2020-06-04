<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\Capability;
use AsyncAws\CloudFormation\Enum\StackStatus;

final class Stack
{
    /**
     * Unique identifier of the stack.
     */
    private $StackId;

    /**
     * The name associated with the stack.
     */
    private $StackName;

    /**
     * The unique ID of the change set.
     */
    private $ChangeSetId;

    /**
     * A user-defined description associated with the stack.
     */
    private $Description;

    /**
     * A list of `Parameter` structures.
     */
    private $Parameters;

    /**
     * The time at which the stack was created.
     */
    private $CreationTime;

    /**
     * The time the stack was deleted.
     */
    private $DeletionTime;

    /**
     * The time the stack was last updated. This field will only be returned if the stack has been updated at least once.
     */
    private $LastUpdatedTime;

    /**
     * The rollback triggers for AWS CloudFormation to monitor during stack creation and updating operations, and for the
     * specified monitoring period afterwards.
     */
    private $RollbackConfiguration;

    /**
     * Current status of the stack.
     */
    private $StackStatus;

    /**
     * Success/failure message associated with the stack status.
     */
    private $StackStatusReason;

    /**
     * Boolean to enable or disable rollback on stack creation failures:.
     */
    private $DisableRollback;

    /**
     * SNS topic ARNs to which stack related events are published.
     */
    private $NotificationARNs;

    /**
     * The amount of time within which stack creation should complete.
     */
    private $TimeoutInMinutes;

    /**
     * The capabilities allowed in the stack.
     */
    private $Capabilities;

    /**
     * A list of output structures.
     */
    private $Outputs;

    /**
     * The Amazon Resource Name (ARN) of an AWS Identity and Access Management (IAM) role that is associated with the stack.
     * During a stack operation, AWS CloudFormation uses this role's credentials to make calls on your behalf.
     */
    private $RoleARN;

    /**
     * A list of `Tag`s that specify information about the stack.
     */
    private $Tags;

    /**
     * Whether termination protection is enabled for the stack.
     */
    private $EnableTerminationProtection;

    /**
     * For nested stacks--stacks created as resources for another stack--the stack ID of the direct parent of this stack.
     * For the first level of nested stacks, the root stack is also the parent stack.
     */
    private $ParentId;

    /**
     * For nested stacks--stacks created as resources for another stack--the stack ID of the top-level stack to which the
     * nested stack ultimately belongs.
     */
    private $RootId;

    /**
     * Information on whether a stack's actual configuration differs, or has *drifted*, from it's expected configuration, as
     * defined in the stack template and any values specified as template parameters. For more information, see Detecting
     * Unregulated Configuration Changes to Stacks and Resources.
     *
     * @see http://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-stack-drift.html
     */
    private $DriftInformation;

    /**
     * @param array{
     *   StackId?: null|string,
     *   StackName: string,
     *   ChangeSetId?: null|string,
     *   Description?: null|string,
     *   Parameters?: null|\AsyncAws\CloudFormation\ValueObject\Parameter[],
     *   CreationTime: \DateTimeImmutable,
     *   DeletionTime?: null|\DateTimeImmutable,
     *   LastUpdatedTime?: null|\DateTimeImmutable,
     *   RollbackConfiguration?: null|\AsyncAws\CloudFormation\ValueObject\RollbackConfiguration|array,
     *   StackStatus: \AsyncAws\CloudFormation\Enum\StackStatus::*,
     *   StackStatusReason?: null|string,
     *   DisableRollback?: null|bool,
     *   NotificationARNs?: null|string[],
     *   TimeoutInMinutes?: null|int,
     *   Capabilities?: null|list<\AsyncAws\CloudFormation\Enum\Capability::*>,
     *   Outputs?: null|\AsyncAws\CloudFormation\ValueObject\Output[],
     *   RoleARN?: null|string,
     *   Tags?: null|\AsyncAws\CloudFormation\ValueObject\Tag[],
     *   EnableTerminationProtection?: null|bool,
     *   ParentId?: null|string,
     *   RootId?: null|string,
     *   DriftInformation?: null|\AsyncAws\CloudFormation\ValueObject\StackDriftInformation|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StackId = $input['StackId'] ?? null;
        $this->StackName = $input['StackName'] ?? null;
        $this->ChangeSetId = $input['ChangeSetId'] ?? null;
        $this->Description = $input['Description'] ?? null;
        $this->Parameters = isset($input['Parameters']) ? array_map([Parameter::class, 'create'], $input['Parameters']) : null;
        $this->CreationTime = $input['CreationTime'] ?? null;
        $this->DeletionTime = $input['DeletionTime'] ?? null;
        $this->LastUpdatedTime = $input['LastUpdatedTime'] ?? null;
        $this->RollbackConfiguration = isset($input['RollbackConfiguration']) ? RollbackConfiguration::create($input['RollbackConfiguration']) : null;
        $this->StackStatus = $input['StackStatus'] ?? null;
        $this->StackStatusReason = $input['StackStatusReason'] ?? null;
        $this->DisableRollback = $input['DisableRollback'] ?? null;
        $this->NotificationARNs = $input['NotificationARNs'] ?? null;
        $this->TimeoutInMinutes = $input['TimeoutInMinutes'] ?? null;
        $this->Capabilities = $input['Capabilities'] ?? null;
        $this->Outputs = isset($input['Outputs']) ? array_map([Output::class, 'create'], $input['Outputs']) : null;
        $this->RoleARN = $input['RoleARN'] ?? null;
        $this->Tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->EnableTerminationProtection = $input['EnableTerminationProtection'] ?? null;
        $this->ParentId = $input['ParentId'] ?? null;
        $this->RootId = $input['RootId'] ?? null;
        $this->DriftInformation = isset($input['DriftInformation']) ? StackDriftInformation::create($input['DriftInformation']) : null;
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
        return $this->Capabilities ?? [];
    }

    public function getChangeSetId(): ?string
    {
        return $this->ChangeSetId;
    }

    public function getCreationTime(): \DateTimeImmutable
    {
        return $this->CreationTime;
    }

    public function getDeletionTime(): ?\DateTimeImmutable
    {
        return $this->DeletionTime;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function getDisableRollback(): ?bool
    {
        return $this->DisableRollback;
    }

    public function getDriftInformation(): ?StackDriftInformation
    {
        return $this->DriftInformation;
    }

    public function getEnableTerminationProtection(): ?bool
    {
        return $this->EnableTerminationProtection;
    }

    public function getLastUpdatedTime(): ?\DateTimeImmutable
    {
        return $this->LastUpdatedTime;
    }

    /**
     * @return string[]
     */
    public function getNotificationARNs(): array
    {
        return $this->NotificationARNs ?? [];
    }

    /**
     * @return Output[]
     */
    public function getOutputs(): array
    {
        return $this->Outputs ?? [];
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->Parameters ?? [];
    }

    public function getParentId(): ?string
    {
        return $this->ParentId;
    }

    public function getRoleARN(): ?string
    {
        return $this->RoleARN;
    }

    public function getRollbackConfiguration(): ?RollbackConfiguration
    {
        return $this->RollbackConfiguration;
    }

    public function getRootId(): ?string
    {
        return $this->RootId;
    }

    public function getStackId(): ?string
    {
        return $this->StackId;
    }

    public function getStackName(): string
    {
        return $this->StackName;
    }

    /**
     * @return StackStatus::*
     */
    public function getStackStatus(): string
    {
        return $this->StackStatus;
    }

    public function getStackStatusReason(): ?string
    {
        return $this->StackStatusReason;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags ?? [];
    }

    public function getTimeoutInMinutes(): ?int
    {
        return $this->TimeoutInMinutes;
    }
}
