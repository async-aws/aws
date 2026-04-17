<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Details of the infrastructure configuration.
 */
final class InfrastructureConfiguration
{
    /**
     * The Amazon Resource Name (ARN) of the infrastructure configuration.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The name of the infrastructure configuration.
     *
     * @var string|null
     */
    private $name;

    /**
     * The description of the infrastructure configuration.
     *
     * @var string|null
     */
    private $description;

    /**
     * The instance types of the infrastructure configuration.
     *
     * @var string[]|null
     */
    private $instanceTypes;

    /**
     * The instance profile of the infrastructure configuration.
     *
     * @var string|null
     */
    private $instanceProfileName;

    /**
     * The security group IDs of the infrastructure configuration.
     *
     * @var string[]|null
     */
    private $securityGroupIds;

    /**
     * The subnet ID of the infrastructure configuration.
     *
     * @var string|null
     */
    private $subnetId;

    /**
     * The logging configuration of the infrastructure configuration.
     *
     * @var Logging|null
     */
    private $logging;

    /**
     * The Amazon EC2 key pair of the infrastructure configuration.
     *
     * @var string|null
     */
    private $keyPair;

    /**
     * The terminate instance on failure configuration of the infrastructure configuration.
     *
     * @var bool|null
     */
    private $terminateInstanceOnFailure;

    /**
     * The Amazon Resource Name (ARN) for the SNS topic to which we send image build event notifications.
     *
     * > EC2 Image Builder is unable to send notifications to SNS topics that are encrypted using keys from other accounts.
     * > The key that is used to encrypt the SNS topic must reside in the account that the Image Builder service runs under.
     *
     * @var string|null
     */
    private $snsTopicArn;

    /**
     * The date on which the infrastructure configuration was created.
     *
     * @var string|null
     */
    private $dateCreated;

    /**
     * The date on which the infrastructure configuration was last updated.
     *
     * @var string|null
     */
    private $dateUpdated;

    /**
     * The tags attached to the resource created by Image Builder.
     *
     * @var array<string, string>|null
     */
    private $resourceTags;

    /**
     * The instance metadata option settings for the infrastructure configuration.
     *
     * @var InstanceMetadataOptions|null
     */
    private $instanceMetadataOptions;

    /**
     * The tags of the infrastructure configuration.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * The instance placement settings that define where the instances that are launched from your image will run.
     *
     * @var Placement|null
     */
    private $placement;

    /**
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   description?: string|null,
     *   instanceTypes?: string[]|null,
     *   instanceProfileName?: string|null,
     *   securityGroupIds?: string[]|null,
     *   subnetId?: string|null,
     *   logging?: Logging|array|null,
     *   keyPair?: string|null,
     *   terminateInstanceOnFailure?: bool|null,
     *   snsTopicArn?: string|null,
     *   dateCreated?: string|null,
     *   dateUpdated?: string|null,
     *   resourceTags?: array<string, string>|null,
     *   instanceMetadataOptions?: InstanceMetadataOptions|array|null,
     *   tags?: array<string, string>|null,
     *   placement?: Placement|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['arn'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->instanceTypes = $input['instanceTypes'] ?? null;
        $this->instanceProfileName = $input['instanceProfileName'] ?? null;
        $this->securityGroupIds = $input['securityGroupIds'] ?? null;
        $this->subnetId = $input['subnetId'] ?? null;
        $this->logging = isset($input['logging']) ? Logging::create($input['logging']) : null;
        $this->keyPair = $input['keyPair'] ?? null;
        $this->terminateInstanceOnFailure = $input['terminateInstanceOnFailure'] ?? null;
        $this->snsTopicArn = $input['snsTopicArn'] ?? null;
        $this->dateCreated = $input['dateCreated'] ?? null;
        $this->dateUpdated = $input['dateUpdated'] ?? null;
        $this->resourceTags = $input['resourceTags'] ?? null;
        $this->instanceMetadataOptions = isset($input['instanceMetadataOptions']) ? InstanceMetadataOptions::create($input['instanceMetadataOptions']) : null;
        $this->tags = $input['tags'] ?? null;
        $this->placement = isset($input['placement']) ? Placement::create($input['placement']) : null;
    }

    /**
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   description?: string|null,
     *   instanceTypes?: string[]|null,
     *   instanceProfileName?: string|null,
     *   securityGroupIds?: string[]|null,
     *   subnetId?: string|null,
     *   logging?: Logging|array|null,
     *   keyPair?: string|null,
     *   terminateInstanceOnFailure?: bool|null,
     *   snsTopicArn?: string|null,
     *   dateCreated?: string|null,
     *   dateUpdated?: string|null,
     *   resourceTags?: array<string, string>|null,
     *   instanceMetadataOptions?: InstanceMetadataOptions|array|null,
     *   tags?: array<string, string>|null,
     *   placement?: Placement|array|null,
     * }|InfrastructureConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function getDateUpdated(): ?string
    {
        return $this->dateUpdated;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getInstanceMetadataOptions(): ?InstanceMetadataOptions
    {
        return $this->instanceMetadataOptions;
    }

    public function getInstanceProfileName(): ?string
    {
        return $this->instanceProfileName;
    }

    /**
     * @return string[]
     */
    public function getInstanceTypes(): array
    {
        return $this->instanceTypes ?? [];
    }

    public function getKeyPair(): ?string
    {
        return $this->keyPair;
    }

    public function getLogging(): ?Logging
    {
        return $this->logging;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPlacement(): ?Placement
    {
        return $this->placement;
    }

    /**
     * @return array<string, string>
     */
    public function getResourceTags(): array
    {
        return $this->resourceTags ?? [];
    }

    /**
     * @return string[]
     */
    public function getSecurityGroupIds(): array
    {
        return $this->securityGroupIds ?? [];
    }

    public function getSnsTopicArn(): ?string
    {
        return $this->snsTopicArn;
    }

    public function getSubnetId(): ?string
    {
        return $this->subnetId;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    public function getTerminateInstanceOnFailure(): ?bool
    {
        return $this->terminateInstanceOnFailure;
    }
}
