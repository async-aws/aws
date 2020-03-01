<?php

namespace AsyncAws\CloudFormation\Result;

use AsyncAws\CloudFormation\Enum\ResourceStatus;

class StackEvent
{
    /**
     * The unique ID name of the instance of the stack.
     */
    private $StackId;

    /**
     * The unique ID of this event.
     */
    private $EventId;

    /**
     * The name associated with a stack.
     */
    private $StackName;

    /**
     * The logical name of the resource specified in the template.
     */
    private $LogicalResourceId;

    /**
     * The name or unique identifier associated with the physical instance of the resource.
     */
    private $PhysicalResourceId;

    /**
     * Type of resource. (For more information, go to  AWS Resource Types Reference in the AWS CloudFormation User Guide.).
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/aws-template-resource-type-ref.html
     */
    private $ResourceType;

    /**
     * Time the status was updated.
     */
    private $Timestamp;

    /**
     * Current status of the resource.
     */
    private $ResourceStatus;

    /**
     * Success/failure message associated with the resource.
     */
    private $ResourceStatusReason;

    /**
     * BLOB of the properties used to create the resource.
     */
    private $ResourceProperties;

    /**
     * The token passed to the operation that generated this event.
     */
    private $ClientRequestToken;

    /**
     * @param array{
     *   StackId: string,
     *   EventId: string,
     *   StackName: string,
     *   LogicalResourceId: ?string,
     *   PhysicalResourceId: ?string,
     *   ResourceType: ?string,
     *   Timestamp: \DateTimeInterface,
     *   ResourceStatus: null|\AsyncAws\CloudFormation\Enum\ResourceStatus::CREATE_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\ResourceStatus::CREATE_FAILED|\AsyncAws\CloudFormation\Enum\ResourceStatus::CREATE_COMPLETE|\AsyncAws\CloudFormation\Enum\ResourceStatus::DELETE_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\ResourceStatus::DELETE_FAILED|\AsyncAws\CloudFormation\Enum\ResourceStatus::DELETE_COMPLETE|\AsyncAws\CloudFormation\Enum\ResourceStatus::DELETE_SKIPPED|\AsyncAws\CloudFormation\Enum\ResourceStatus::UPDATE_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\ResourceStatus::UPDATE_FAILED|\AsyncAws\CloudFormation\Enum\ResourceStatus::UPDATE_COMPLETE|\AsyncAws\CloudFormation\Enum\ResourceStatus::IMPORT_FAILED|\AsyncAws\CloudFormation\Enum\ResourceStatus::IMPORT_COMPLETE|\AsyncAws\CloudFormation\Enum\ResourceStatus::IMPORT_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\ResourceStatus::IMPORT_ROLLBACK_IN_PROGRESS|\AsyncAws\CloudFormation\Enum\ResourceStatus::IMPORT_ROLLBACK_FAILED|\AsyncAws\CloudFormation\Enum\ResourceStatus::IMPORT_ROLLBACK_COMPLETE,
     *   ResourceStatusReason: ?string,
     *   ResourceProperties: ?string,
     *   ClientRequestToken: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StackId = $input['StackId'];
        $this->EventId = $input['EventId'];
        $this->StackName = $input['StackName'];
        $this->LogicalResourceId = $input['LogicalResourceId'];
        $this->PhysicalResourceId = $input['PhysicalResourceId'];
        $this->ResourceType = $input['ResourceType'];
        $this->Timestamp = $input['Timestamp'];
        $this->ResourceStatus = $input['ResourceStatus'];
        $this->ResourceStatusReason = $input['ResourceStatusReason'];
        $this->ResourceProperties = $input['ResourceProperties'];
        $this->ClientRequestToken = $input['ClientRequestToken'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->ClientRequestToken;
    }

    public function getEventId(): string
    {
        return $this->EventId;
    }

    public function getLogicalResourceId(): ?string
    {
        return $this->LogicalResourceId;
    }

    public function getPhysicalResourceId(): ?string
    {
        return $this->PhysicalResourceId;
    }

    public function getResourceProperties(): ?string
    {
        return $this->ResourceProperties;
    }

    /**
     * @return ResourceStatus::CREATE_IN_PROGRESS|ResourceStatus::CREATE_FAILED|ResourceStatus::CREATE_COMPLETE|ResourceStatus::DELETE_IN_PROGRESS|ResourceStatus::DELETE_FAILED|ResourceStatus::DELETE_COMPLETE|ResourceStatus::DELETE_SKIPPED|ResourceStatus::UPDATE_IN_PROGRESS|ResourceStatus::UPDATE_FAILED|ResourceStatus::UPDATE_COMPLETE|ResourceStatus::IMPORT_FAILED|ResourceStatus::IMPORT_COMPLETE|ResourceStatus::IMPORT_IN_PROGRESS|ResourceStatus::IMPORT_ROLLBACK_IN_PROGRESS|ResourceStatus::IMPORT_ROLLBACK_FAILED|ResourceStatus::IMPORT_ROLLBACK_COMPLETE|null
     */
    public function getResourceStatus(): ?string
    {
        return $this->ResourceStatus;
    }

    public function getResourceStatusReason(): ?string
    {
        return $this->ResourceStatusReason;
    }

    public function getResourceType(): ?string
    {
        return $this->ResourceType;
    }

    public function getStackId(): string
    {
        return $this->StackId;
    }

    public function getStackName(): string
    {
        return $this->StackName;
    }

    public function getTimestamp(): \DateTimeInterface
    {
        return $this->Timestamp;
    }
}
