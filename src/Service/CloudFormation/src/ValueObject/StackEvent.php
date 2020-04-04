<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\ResourceStatus;

final class StackEvent
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
     *   LogicalResourceId?: null|string,
     *   PhysicalResourceId?: null|string,
     *   ResourceType?: null|string,
     *   Timestamp: \DateTimeImmutable,
     *   ResourceStatus?: null|\AsyncAws\CloudFormation\Enum\ResourceStatus::*,
     *   ResourceStatusReason?: null|string,
     *   ResourceProperties?: null|string,
     *   ClientRequestToken?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StackId = $input['StackId'] ?? null;
        $this->EventId = $input['EventId'] ?? null;
        $this->StackName = $input['StackName'] ?? null;
        $this->LogicalResourceId = $input['LogicalResourceId'] ?? null;
        $this->PhysicalResourceId = $input['PhysicalResourceId'] ?? null;
        $this->ResourceType = $input['ResourceType'] ?? null;
        $this->Timestamp = $input['Timestamp'] ?? null;
        $this->ResourceStatus = $input['ResourceStatus'] ?? null;
        $this->ResourceStatusReason = $input['ResourceStatusReason'] ?? null;
        $this->ResourceProperties = $input['ResourceProperties'] ?? null;
        $this->ClientRequestToken = $input['ClientRequestToken'] ?? null;
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
     * @return ResourceStatus::*|null
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

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->Timestamp;
    }
}
