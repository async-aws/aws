<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;

/**
 * An image recipe.
 */
final class ImageRecipe
{
    /**
     * The Amazon Resource Name (ARN) of the image recipe.
     *
     * @var string|null
     */
    private $arn;

    /**
     * Specifies which type of image is created by the recipe - an AMI or a container image.
     *
     * @var ImageType::*|null
     */
    private $type;

    /**
     * The name of the image recipe.
     *
     * @var string|null
     */
    private $name;

    /**
     * The description of the image recipe.
     *
     * @var string|null
     */
    private $description;

    /**
     * The platform of the image recipe.
     *
     * @var Platform::*|null
     */
    private $platform;

    /**
     * The owner of the image recipe.
     *
     * @var string|null
     */
    private $owner;

    /**
     * The version of the image recipe.
     *
     * @var string|null
     */
    private $version;

    /**
     * The components that are included in the image recipe. Recipes require a minimum of one build component, and can have
     * a maximum of 20 build and test components in any combination.
     *
     * @var ComponentConfiguration[]|null
     */
    private $components;

    /**
     * The base image for customizations specified in the image recipe. You can specify the parent image using one of the
     * following options:
     *
     * - AMI ID
     * - Image Builder image Amazon Resource Name (ARN)
     * - Amazon Web Services Systems Manager (SSM) Parameter Store Parameter, prefixed by `ssm:`, followed by the parameter
     *   name or ARN.
     * - Amazon Web Services Marketplace product ID
     *
     * @var string|null
     */
    private $parentImage;

    /**
     * The block device mappings to apply when creating images from this recipe.
     *
     * @var InstanceBlockDeviceMapping[]|null
     */
    private $blockDeviceMappings;

    /**
     * The date on which this image recipe was created.
     *
     * @var string|null
     */
    private $dateCreated;

    /**
     * The tags of the image recipe.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * The working directory to be used during build and test workflows.
     *
     * @var string|null
     */
    private $workingDirectory;

    /**
     * Before you create a new AMI, Image Builder launches temporary Amazon EC2 instances to build and test your image
     * configuration. Instance configuration adds a layer of control over those instances. You can define settings and add
     * scripts to run when an instance is launched from your AMI.
     *
     * @var AdditionalInstanceConfiguration|null
     */
    private $additionalInstanceConfiguration;

    /**
     * Tags that are applied to the AMI that Image Builder creates during the Build phase prior to image distribution.
     *
     * @var array<string, string>|null
     */
    private $amiTags;

    /**
     * @param array{
     *   arn?: string|null,
     *   type?: ImageType::*|null,
     *   name?: string|null,
     *   description?: string|null,
     *   platform?: Platform::*|null,
     *   owner?: string|null,
     *   version?: string|null,
     *   components?: array<ComponentConfiguration|array>|null,
     *   parentImage?: string|null,
     *   blockDeviceMappings?: array<InstanceBlockDeviceMapping|array>|null,
     *   dateCreated?: string|null,
     *   tags?: array<string, string>|null,
     *   workingDirectory?: string|null,
     *   additionalInstanceConfiguration?: AdditionalInstanceConfiguration|array|null,
     *   amiTags?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['arn'] ?? null;
        $this->type = $input['type'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->platform = $input['platform'] ?? null;
        $this->owner = $input['owner'] ?? null;
        $this->version = $input['version'] ?? null;
        $this->components = isset($input['components']) ? array_map([ComponentConfiguration::class, 'create'], $input['components']) : null;
        $this->parentImage = $input['parentImage'] ?? null;
        $this->blockDeviceMappings = isset($input['blockDeviceMappings']) ? array_map([InstanceBlockDeviceMapping::class, 'create'], $input['blockDeviceMappings']) : null;
        $this->dateCreated = $input['dateCreated'] ?? null;
        $this->tags = $input['tags'] ?? null;
        $this->workingDirectory = $input['workingDirectory'] ?? null;
        $this->additionalInstanceConfiguration = isset($input['additionalInstanceConfiguration']) ? AdditionalInstanceConfiguration::create($input['additionalInstanceConfiguration']) : null;
        $this->amiTags = $input['amiTags'] ?? null;
    }

    /**
     * @param array{
     *   arn?: string|null,
     *   type?: ImageType::*|null,
     *   name?: string|null,
     *   description?: string|null,
     *   platform?: Platform::*|null,
     *   owner?: string|null,
     *   version?: string|null,
     *   components?: array<ComponentConfiguration|array>|null,
     *   parentImage?: string|null,
     *   blockDeviceMappings?: array<InstanceBlockDeviceMapping|array>|null,
     *   dateCreated?: string|null,
     *   tags?: array<string, string>|null,
     *   workingDirectory?: string|null,
     *   additionalInstanceConfiguration?: AdditionalInstanceConfiguration|array|null,
     *   amiTags?: array<string, string>|null,
     * }|ImageRecipe $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAdditionalInstanceConfiguration(): ?AdditionalInstanceConfiguration
    {
        return $this->additionalInstanceConfiguration;
    }

    /**
     * @return array<string, string>
     */
    public function getAmiTags(): array
    {
        return $this->amiTags ?? [];
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    /**
     * @return InstanceBlockDeviceMapping[]
     */
    public function getBlockDeviceMappings(): array
    {
        return $this->blockDeviceMappings ?? [];
    }

    /**
     * @return ComponentConfiguration[]
     */
    public function getComponents(): array
    {
        return $this->components ?? [];
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function getParentImage(): ?string
    {
        return $this->parentImage;
    }

    /**
     * @return Platform::*|null
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    /**
     * @return array<string, string>
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @return ImageType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getWorkingDirectory(): ?string
    {
        return $this->workingDirectory;
    }
}
