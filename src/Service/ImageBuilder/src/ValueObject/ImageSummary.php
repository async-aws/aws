<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\BuildType;
use AsyncAws\ImageBuilder\Enum\ImageSource;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;

/**
 * An image summary.
 */
final class ImageSummary
{
    /**
     * The Amazon Resource Name (ARN) of the image.
     *
     * @var string|null
     */
    private $arn;

    /**
     * The name of the image.
     *
     * @var string|null
     */
    private $name;

    /**
     * Specifies whether this image produces an AMI or a container image.
     *
     * @var ImageType::*|null
     */
    private $type;

    /**
     * The version of the image.
     *
     * @var string|null
     */
    private $version;

    /**
     * The image operating system platform, such as Linux or Windows.
     *
     * @var Platform::*|null
     */
    private $platform;

    /**
     * The operating system version of the instances that launch from this image. For example, Amazon Linux 2, Ubuntu 18, or
     * Microsoft Windows Server 2019.
     *
     * @var string|null
     */
    private $osVersion;

    /**
     * The state of the image.
     *
     * @var ImageState|null
     */
    private $state;

    /**
     * The owner of the image.
     *
     * @var string|null
     */
    private $owner;

    /**
     * The date on which Image Builder created this image.
     *
     * @var string|null
     */
    private $dateCreated;

    /**
     * The output resources that Image Builder produced when it created this image.
     *
     * @var OutputResources|null
     */
    private $outputResources;

    /**
     * The tags that apply to this image.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * Indicates the type of build that created this image. The build can be initiated in the following ways:
     *
     * - **USER_INITIATED** – A manual pipeline build request.
     * - **SCHEDULED** – A pipeline build initiated by a cron expression in the Image Builder pipeline, or from
     *   EventBridge.
     * - **IMPORT** – A VM import created the image to use as the base image for the recipe.
     * - **IMPORT_ISO** – An ISO disk import created the image.
     *
     * @var BuildType::*|null
     */
    private $buildType;

    /**
     * The origin of the base image that Image Builder used to build this image.
     *
     * @var ImageSource::*|null
     */
    private $imageSource;

    /**
     * The time when deprecation occurs for an image resource. This can be a past or future date.
     *
     * @var \DateTimeImmutable|null
     */
    private $deprecationTime;

    /**
     * Identifies the last runtime instance of the lifecycle policy to take action on the image.
     *
     * @var string|null
     */
    private $lifecycleExecutionId;

    /**
     * The logging configuration that's defined for the image.
     *
     * @var ImageLoggingConfiguration|null
     */
    private $loggingConfiguration;

    /**
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   type?: ImageType::*|null,
     *   version?: string|null,
     *   platform?: Platform::*|null,
     *   osVersion?: string|null,
     *   state?: ImageState|array|null,
     *   owner?: string|null,
     *   dateCreated?: string|null,
     *   outputResources?: OutputResources|array|null,
     *   tags?: array<string, string>|null,
     *   buildType?: BuildType::*|null,
     *   imageSource?: ImageSource::*|null,
     *   deprecationTime?: \DateTimeImmutable|null,
     *   lifecycleExecutionId?: string|null,
     *   loggingConfiguration?: ImageLoggingConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['arn'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->type = $input['type'] ?? null;
        $this->version = $input['version'] ?? null;
        $this->platform = $input['platform'] ?? null;
        $this->osVersion = $input['osVersion'] ?? null;
        $this->state = isset($input['state']) ? ImageState::create($input['state']) : null;
        $this->owner = $input['owner'] ?? null;
        $this->dateCreated = $input['dateCreated'] ?? null;
        $this->outputResources = isset($input['outputResources']) ? OutputResources::create($input['outputResources']) : null;
        $this->tags = $input['tags'] ?? null;
        $this->buildType = $input['buildType'] ?? null;
        $this->imageSource = $input['imageSource'] ?? null;
        $this->deprecationTime = $input['deprecationTime'] ?? null;
        $this->lifecycleExecutionId = $input['lifecycleExecutionId'] ?? null;
        $this->loggingConfiguration = isset($input['loggingConfiguration']) ? ImageLoggingConfiguration::create($input['loggingConfiguration']) : null;
    }

    /**
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   type?: ImageType::*|null,
     *   version?: string|null,
     *   platform?: Platform::*|null,
     *   osVersion?: string|null,
     *   state?: ImageState|array|null,
     *   owner?: string|null,
     *   dateCreated?: string|null,
     *   outputResources?: OutputResources|array|null,
     *   tags?: array<string, string>|null,
     *   buildType?: BuildType::*|null,
     *   imageSource?: ImageSource::*|null,
     *   deprecationTime?: \DateTimeImmutable|null,
     *   lifecycleExecutionId?: string|null,
     *   loggingConfiguration?: ImageLoggingConfiguration|array|null,
     * }|ImageSummary $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArn(): ?string
    {
        return $this->arn;
    }

    /**
     * @return BuildType::*|null
     */
    public function getBuildType(): ?string
    {
        return $this->buildType;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function getDeprecationTime(): ?\DateTimeImmutable
    {
        return $this->deprecationTime;
    }

    /**
     * @return ImageSource::*|null
     */
    public function getImageSource(): ?string
    {
        return $this->imageSource;
    }

    public function getLifecycleExecutionId(): ?string
    {
        return $this->lifecycleExecutionId;
    }

    public function getLoggingConfiguration(): ?ImageLoggingConfiguration
    {
        return $this->loggingConfiguration;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOsVersion(): ?string
    {
        return $this->osVersion;
    }

    public function getOutputResources(): ?OutputResources
    {
        return $this->outputResources;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    /**
     * @return Platform::*|null
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function getState(): ?ImageState
    {
        return $this->state;
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
}
