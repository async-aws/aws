<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\Enum\Capability;
use AsyncAws\CloudFormation\Enum\StackStatus;

class Stack
{
    private $StackId;

    private $StackName;

    private $ChangeSetId;

    private $Description;

    private $Parameters = [];

    private $CreationTime;

    private $DeletionTime;

    private $LastUpdatedTime;

    private $RollbackConfiguration;

    private $StackStatus;

    private $StackStatusReason;

    private $DisableRollback;

    private $NotificationARNs = [];

    private $TimeoutInMinutes;

    private $Capabilities = [];

    private $Outputs = [];

    private $RoleARN;

    private $Tags = [];

    private $EnableTerminationProtection;

    private $ParentId;

    private $RootId;

    private $DriftInformation;

    /**
     * @param array{
     *   StackId: null|string,
     *   StackName: string,
     *   ChangeSetId: null|string,
     *   Description: null|string,
     *   Parameters: null|\AsyncAws\CloudFormation\Result\Parameter[],
     *   CreationTime: \DateTimeInterface,
     *   DeletionTime: null|\DateTimeInterface,
     *   LastUpdatedTime: null|\DateTimeInterface,
     *   RollbackConfiguration: null|\AsyncAws\CloudFormation\Result\RollbackConfiguration|array,
     *   StackStatus: \AsyncAws\CloudFormation\Enum\StackStatus::*,
     *   StackStatusReason: null|string,
     *   DisableRollback: null|bool,
     *   NotificationARNs: null|string[],
     *   TimeoutInMinutes: null|int,
     *   Capabilities: null|list<\AsyncAws\CloudFormation\Enum\Capability::*>,
     *   Outputs: null|\AsyncAws\CloudFormation\Result\Output[],
     *   RoleARN: null|string,
     *   Tags: null|\AsyncAws\CloudFormation\Result\Tag[],
     *   EnableTerminationProtection: null|bool,
     *   ParentId: null|string,
     *   RootId: null|string,
     *   DriftInformation: null|\AsyncAws\CloudFormation\Result\StackDriftInformation|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StackId = $input['StackId'];
        $this->StackName = $input['StackName'];
        $this->ChangeSetId = $input['ChangeSetId'];
        $this->Description = $input['Description'];
        $this->Parameters = array_map(function ($item) { return Parameter::create($item); }, $input['Parameters'] ?? []);
        $this->CreationTime = $input['CreationTime'];
        $this->DeletionTime = $input['DeletionTime'];
        $this->LastUpdatedTime = $input['LastUpdatedTime'];
        $this->RollbackConfiguration = isset($input['RollbackConfiguration']) ? RollbackConfiguration::create($input['RollbackConfiguration']) : null;
        $this->StackStatus = $input['StackStatus'];
        $this->StackStatusReason = $input['StackStatusReason'];
        $this->DisableRollback = $input['DisableRollback'];
        $this->NotificationARNs = $input['NotificationARNs'] ?? [];
        $this->TimeoutInMinutes = $input['TimeoutInMinutes'];
        $this->Capabilities = $input['Capabilities'] ?? [];
        $this->Outputs = array_map(function ($item) { return Output::create($item); }, $input['Outputs'] ?? []);
        $this->RoleARN = $input['RoleARN'];
        $this->Tags = array_map(function ($item) { return Tag::create($item); }, $input['Tags'] ?? []);
        $this->EnableTerminationProtection = $input['EnableTerminationProtection'];
        $this->ParentId = $input['ParentId'];
        $this->RootId = $input['RootId'];
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
        return $this->Capabilities;
    }

    /**
     * The unique ID of the change set.
     */
    public function getChangeSetId(): ?string
    {
        return $this->ChangeSetId;
    }

    /**
     * The time at which the stack was created.
     */
    public function getCreationTime(): \DateTimeInterface
    {
        return $this->CreationTime;
    }

    /**
     * The time the stack was deleted.
     */
    public function getDeletionTime(): ?\DateTimeInterface
    {
        return $this->DeletionTime;
    }

    /**
     * A user-defined description associated with the stack.
     */
    public function getDescription(): ?string
    {
        return $this->Description;
    }

    /**
     * Boolean to enable or disable rollback on stack creation failures:.
     */
    public function getDisableRollback(): ?bool
    {
        return $this->DisableRollback;
    }

    /**
     * Information on whether a stack's actual configuration differs, or has *drifted*, from it's expected configuration, as
     * defined in the stack template and any values specified as template parameters. For more information, see Detecting
     * Unregulated Configuration Changes to Stacks and Resources.
     *
     * @see http://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/using-cfn-stack-drift.html
     */
    public function getDriftInformation(): ?StackDriftInformation
    {
        return $this->DriftInformation;
    }

    /**
     * Whether termination protection is enabled for the stack.
     */
    public function getEnableTerminationProtection(): ?bool
    {
        return $this->EnableTerminationProtection;
    }

    /**
     * The time the stack was last updated. This field will only be returned if the stack has been updated at least once.
     */
    public function getLastUpdatedTime(): ?\DateTimeInterface
    {
        return $this->LastUpdatedTime;
    }

    /**
     * @return string[]
     */
    public function getNotificationARNs(): array
    {
        return $this->NotificationARNs;
    }

    /**
     * @return Output[]
     */
    public function getOutputs(): array
    {
        return $this->Outputs;
    }

    /**
     * @return Parameter[]
     */
    public function getParameters(): array
    {
        return $this->Parameters;
    }

    /**
     * For nested stacks--stacks created as resources for another stack--the stack ID of the direct parent of this stack.
     * For the first level of nested stacks, the root stack is also the parent stack.
     */
    public function getParentId(): ?string
    {
        return $this->ParentId;
    }

    /**
     * The Amazon Resource Name (ARN) of an AWS Identity and Access Management (IAM) role that is associated with the stack.
     * During a stack operation, AWS CloudFormation uses this role's credentials to make calls on your behalf.
     */
    public function getRoleARN(): ?string
    {
        return $this->RoleARN;
    }

    /**
     * The rollback triggers for AWS CloudFormation to monitor during stack creation and updating operations, and for the
     * specified monitoring period afterwards.
     */
    public function getRollbackConfiguration(): ?RollbackConfiguration
    {
        return $this->RollbackConfiguration;
    }

    /**
     * For nested stacks--stacks created as resources for another stack--the stack ID of the top-level stack to which the
     * nested stack ultimately belongs.
     */
    public function getRootId(): ?string
    {
        return $this->RootId;
    }

    /**
     * Unique identifier of the stack.
     */
    public function getStackId(): ?string
    {
        return $this->StackId;
    }

    /**
     * The name associated with the stack.
     */
    public function getStackName(): string
    {
        return $this->StackName;
    }

    /**
     * Current status of the stack.
     *
     * @return StackStatus::*
     */
    public function getStackStatus(): string
    {
        return $this->StackStatus;
    }

    /**
     * Success/failure message associated with the stack status.
     */
    public function getStackStatusReason(): ?string
    {
        return $this->StackStatusReason;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->Tags;
    }

    /**
     * The amount of time within which stack creation should complete.
     */
    public function getTimeoutInMinutes(): ?int
    {
        return $this->TimeoutInMinutes;
    }
}
