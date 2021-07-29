<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\CloudFormation\Enum\ResourceStatus;

/**
 * The StackEvent data type.
 */
final class StackEvent
{
    /**
     * The unique ID name of the instance of the stack.
     */
    private $stackId;

    /**
     * The unique ID of this event.
     */
    private $eventId;

    /**
     * The name associated with a stack.
     */
    private $stackName;

    /**
     * The logical name of the resource specified in the template.
     */
    private $logicalResourceId;

    /**
     * The name or unique identifier associated with the physical instance of the resource.
     */
    private $physicalResourceId;

    /**
     * Type of resource. (For more information, go to Amazon Web Services Resource Types Reference in the CloudFormation
     * User Guide.).
     *
     * @see https://docs.aws.amazon.com/AWSCloudFormation/latest/UserGuide/aws-template-resource-type-ref.html
     */
    private $resourceType;

    /**
     * Time the status was updated.
     */
    private $timestamp;

    /**
     * Current status of the resource.
     */
    private $resourceStatus;

    /**
     * Success/failure message associated with the resource.
     */
    private $resourceStatusReason;

    /**
     * BLOB of the properties used to create the resource.
     */
    private $resourceProperties;

    /**
     * The token passed to the operation that generated this event.
     */
    private $clientRequestToken;

    /**
     * @param array{
     *   StackId: string,
     *   EventId: string,
     *   StackName: string,
     *   LogicalResourceId?: null|string,
     *   PhysicalResourceId?: null|string,
     *   ResourceType?: null|string,
     *   Timestamp: \DateTimeImmutable,
     *   ResourceStatus?: null|ResourceStatus::*,
     *   ResourceStatusReason?: null|string,
     *   ResourceProperties?: null|string,
     *   ClientRequestToken?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->stackId = $input['StackId'] ?? null;
        $this->eventId = $input['EventId'] ?? null;
        $this->stackName = $input['StackName'] ?? null;
        $this->logicalResourceId = $input['LogicalResourceId'] ?? null;
        $this->physicalResourceId = $input['PhysicalResourceId'] ?? null;
        $this->resourceType = $input['ResourceType'] ?? null;
        $this->timestamp = $input['Timestamp'] ?? null;
        $this->resourceStatus = $input['ResourceStatus'] ?? null;
        $this->resourceStatusReason = $input['ResourceStatusReason'] ?? null;
        $this->resourceProperties = $input['ResourceProperties'] ?? null;
        $this->clientRequestToken = $input['ClientRequestToken'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientRequestToken(): ?string
    {
        return $this->clientRequestToken;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getLogicalResourceId(): ?string
    {
        return $this->logicalResourceId;
    }

    public function getPhysicalResourceId(): ?string
    {
        return $this->physicalResourceId;
    }

    public function getResourceProperties(): ?string
    {
        return $this->resourceProperties;
    }

    /**
     * @return ResourceStatus::*|null
     */
    public function getResourceStatus(): ?string
    {
        return $this->resourceStatus;
    }

    public function getResourceStatusReason(): ?string
    {
        return $this->resourceStatusReason;
    }

    public function getResourceType(): ?string
    {
        return $this->resourceType;
    }

    public function getStackId(): string
    {
        return $this->stackId;
    }

    public function getStackName(): string
    {
        return $this->stackName;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
