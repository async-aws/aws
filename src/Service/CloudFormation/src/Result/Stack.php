<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\Enum\StackStatus;

class Stack
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
    private $Parameters = [];

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
    private $NotificationARNs = [];

    /**
     * The amount of time within which stack creation should complete.
     */
    private $TimeoutInMinutes;

    /**
     * The capabilities allowed in the stack.
     */
    private $Capabilities = [];

    /**
     * A list of output structures.
     */
    private $Outputs = [];

    /**
     * The Amazon Resource Name (ARN) of an AWS Identity and Access Management (IAM) role that is associated with the stack.
     * During a stack operation, AWS CloudFormation uses this role's credentials to make calls on your behalf.
     */
    private $RoleARN;

    /**
     * A list of `Tag`s that specify information about the stack.
     */
    private $Tags = [];

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
     *   StackId: ?string,
     *   StackName: string,
     *   ChangeSetId: ?string,
     *   Description: ?string,
     *   Parameters: ?\AsyncAws\CloudFormation\Result\Parameter[],
     *   CreationTime: \DateTimeInterface,
     *   DeletionTime: ?\DateTimeInterface,
     *   LastUpdatedTime: ?\DateTimeInterface,
     *   RollbackConfiguration: null|\AsyncAws\CloudFormation\Result\RollbackConfiguration|array,
     *   StackStatus: \AsyncAws\CloudFormation\Enum\StackStatus::CREATE_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::CREATE_FAILED|\AsyncAws\CloudFormation\Enum\StackStatus::CREATE_COMPLETE|\AsyncAws\CloudFormation\Enum\StackStatus::ROLLBACK_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::ROLLBACK_FAILED|\AsyncAws\CloudFormation\Enum\StackStatus::ROLLBACK_COMPLETE|\AsyncAws\CloudFormation\Enum\StackStatus::DELETE_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::DELETE_FAILED|\AsyncAws\CloudFormation\Enum\StackStatus::DELETE_COMPLETE|\AsyncAws\CloudFormation\Enum\StackStatus::UPDATE_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::UPDATE_COMPLETE_CLEANUP_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::UPDATE_COMPLETE|\AsyncAws\CloudFormation\Enum\StackStatus::UPDATE_ROLLBACK_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::UPDATE_ROLLBACK_FAILED|\AsyncAws\CloudFormation\Enum\StackStatus::UPDATE_ROLLBACK_COMPLETE_CLEANUP_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::UPDATE_ROLLBACK_COMPLETE|\AsyncAws\CloudFormation\Enum\StackStatus::REVIEW_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::IMPORT_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::IMPORT_COMPLETE|\AsyncAws\CloudFormation\Enum\StackStatus::IMPORT_ROLLBACK_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\StackStatus::IMPORT_ROLLBACK_FAILED|\AsyncAws\CloudFormation\Enum\StackStatus::IMPORT_ROLLBACK_COMPLETE,
     *   StackStatusReason: ?string,
     *   DisableRollback: ?bool,
     *   NotificationARNs: ?string[],
     *   TimeoutInMinutes: ?int,
     *   Capabilities: ?string[],
     *   Outputs: ?\AsyncAws\CloudFormation\Result\Output[],
     *   RoleARN: ?string,
     *   Tags: ?\AsyncAws\CloudFormation\Result\Tag[],
     *   EnableTerminationProtection: ?bool,
     *   ParentId: ?string,
     *   RootId: ?string,
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
     * @return string[]
     */
    public function getCapabilities(): array
    {
        return $this->Capabilities;
    }

    public function getChangeSetId(): ?string
    {
        return $this->ChangeSetId;
    }

    public function getCreationTime(): \DateTimeInterface
    {
        return $this->CreationTime;
    }

    public function getDeletionTime(): ?\DateTimeInterface
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
     * @return StackStatus::CREATE_IN_PROGRESS|StackStatus::CREATE_FAILED|StackStatus::CREATE_COMPLETE|StackStatus::ROLLBACK_IN_PROGRESS|StackStatus::ROLLBACK_FAILED|StackStatus::ROLLBACK_COMPLETE|StackStatus::DELETE_IN_PROGRESS|StackStatus::DELETE_FAILED|StackStatus::DELETE_COMPLETE|StackStatus::UPDATE_IN_PROGRESS|StackStatus::UPDATE_COMPLETE_CLEANUP_IN_PROGRESS|StackStatus::UPDATE_COMPLETE|StackStatus::UPDATE_ROLLBACK_IN_PROGRESS|StackStatus::UPDATE_ROLLBACK_FAILED|StackStatus::UPDATE_ROLLBACK_COMPLETE_CLEANUP_IN_PROGRESS|StackStatus::UPDATE_ROLLBACK_COMPLETE|StackStatus::REVIEW_IN_PROGRESS|StackStatus::IMPORT_IN_PROGRESS|StackStatus::IMPORT_COMPLETE|StackStatus::IMPORT_ROLLBACK_IN_PROGRESS|StackStatus::IMPORT_ROLLBACK_FAILED|StackStatus::IMPORT_ROLLBACK_COMPLETE
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
        return $this->Tags;
    }

    public function getTimeoutInMinutes(): ?int
    {
        return $this->TimeoutInMinutes;
    }
}
