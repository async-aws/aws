<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\BuildType;
use AsyncAws\ImageBuilder\Enum\ImageSource;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;

/**
 * An Image Builder image resource that keeps track of all of the settings used to create, configure, and distribute
 * output for that image. You must specify exactly one recipe for the image – either a container recipe
 * (`containerRecipe`), which creates a container image, or an image recipe (`imageRecipe`), which creates an AMI.
 */
final class Image
{
    /**
     * The Amazon Resource Name (ARN) of the image.
     *
     * > Semantic versioning is included in each object's Amazon Resource Name (ARN), at the level that applies to that
     * > object as follows:
     * >
     * > 1. Versionless ARNs and Name ARNs do not include specific values in any of the nodes. The nodes are either left off
     * >    entirely, or they are specified as wildcards, for example: x.x.x.
     * > 2. Version ARNs have only the first three nodes: <major>.<minor>.<patch>
     * > 3. Build version ARNs have all four nodes, and point to a specific build for a specific version of an object.
     * >
     *
     * @var string|null
     */
    private $arn;

    /**
     * Specifies whether this image produces an AMI or a container image.
     *
     * @var ImageType::*|null
     */
    private $type;

    /**
     * The name of the image.
     *
     * @var string|null
     */
    private $name;

    /**
     * The semantic version of the image.
     *
     * > The semantic version has four nodes: <major>.<minor>.<patch>/<build>. You can assign values
     * > for the first three, and can filter on all of them.
     * >
     * > **Assignment:** For the first three nodes you can assign any positive integer value, including zero, with an upper
     * > limit of 2^30-1, or 1073741823 for each node. Image Builder automatically assigns the build number to the fourth
     * > node.
     * >
     * > **Patterns:** You can use any numeric pattern that adheres to the assignment requirements for the nodes that you
     * > can assign. For example, you might choose a software version pattern, such as 1.0.0, or a date, such as 2021.01.01.
     * >
     * > **Filtering:** With semantic versioning, you have the flexibility to use wildcards (x) to specify the most recent
     * > versions or nodes when selecting the base image or components for your recipe. When you use a wildcard in any node,
     * > all nodes to the right of the first wildcard must also be wildcards.
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
     * Indicates whether Image Builder collects additional information about the image, such as the operating system (OS)
     * version and package list.
     *
     * @var bool|null
     */
    private $enhancedImageMetadataEnabled;

    /**
     * The operating system version for instances that launch from this image. For example, Amazon Linux 2, Ubuntu 18, or
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
     * For images that distribute an AMI, this is the image recipe that Image Builder used to create the image. For
     * container images, this is empty.
     *
     * @var ImageRecipe|null
     */
    private $imageRecipe;

    /**
     * For container images, this is the container recipe that Image Builder used to create the image. For images that
     * distribute an AMI, this is empty.
     *
     * @var ContainerRecipe|null
     */
    private $containerRecipe;

    /**
     * The name of the image pipeline that created this image.
     *
     * @var string|null
     */
    private $sourcePipelineName;

    /**
     * The Amazon Resource Name (ARN) of the image pipeline that created this image.
     *
     * @var string|null
     */
    private $sourcePipelineArn;

    /**
     * The infrastructure that Image Builder used to create this image.
     *
     * @var InfrastructureConfiguration|null
     */
    private $infrastructureConfiguration;

    /**
     * The distribution configuration that Image Builder used to create this image.
     *
     * @var DistributionConfiguration|null
     */
    private $distributionConfiguration;

    /**
     * The image tests that ran when that Image Builder created this image.
     *
     * @var ImageTestsConfiguration|null
     */
    private $imageTestsConfiguration;

    /**
     * The date on which Image Builder created this image.
     *
     * @var string|null
     */
    private $dateCreated;

    /**
     * The output resources that Image Builder produces for this image.
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
     * Contains information about the current state of scans for this image.
     *
     * @var ImageScanState|null
     */
    private $scanState;

    /**
     * Contains settings for vulnerability scans.
     *
     * @var ImageScanningConfiguration|null
     */
    private $imageScanningConfiguration;

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
     * The name or Amazon Resource Name (ARN) for the IAM role you create that grants Image Builder access to perform
     * workflow actions.
     *
     * @var string|null
     */
    private $executionRole;

    /**
     * Contains the build and test workflows that are associated with the image.
     *
     * @var WorkflowConfiguration[]|null
     */
    private $workflows;

    /**
     * The logging configuration that's defined for the image. Image Builder uses the defined settings to direct execution
     * log output during image creation.
     *
     * @var ImageLoggingConfiguration|null
     */
    private $loggingConfiguration;

    /**
     * @param array{
     *   arn?: string|null,
     *   type?: ImageType::*|null,
     *   name?: string|null,
     *   version?: string|null,
     *   platform?: Platform::*|null,
     *   enhancedImageMetadataEnabled?: bool|null,
     *   osVersion?: string|null,
     *   state?: ImageState|array|null,
     *   imageRecipe?: ImageRecipe|array|null,
     *   containerRecipe?: ContainerRecipe|array|null,
     *   sourcePipelineName?: string|null,
     *   sourcePipelineArn?: string|null,
     *   infrastructureConfiguration?: InfrastructureConfiguration|array|null,
     *   distributionConfiguration?: DistributionConfiguration|array|null,
     *   imageTestsConfiguration?: ImageTestsConfiguration|array|null,
     *   dateCreated?: string|null,
     *   outputResources?: OutputResources|array|null,
     *   tags?: array<string, string>|null,
     *   buildType?: BuildType::*|null,
     *   imageSource?: ImageSource::*|null,
     *   scanState?: ImageScanState|array|null,
     *   imageScanningConfiguration?: ImageScanningConfiguration|array|null,
     *   deprecationTime?: \DateTimeImmutable|null,
     *   lifecycleExecutionId?: string|null,
     *   executionRole?: string|null,
     *   workflows?: array<WorkflowConfiguration|array>|null,
     *   loggingConfiguration?: ImageLoggingConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['arn'] ?? null;
        $this->type = $input['type'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->version = $input['version'] ?? null;
        $this->platform = $input['platform'] ?? null;
        $this->enhancedImageMetadataEnabled = $input['enhancedImageMetadataEnabled'] ?? null;
        $this->osVersion = $input['osVersion'] ?? null;
        $this->state = isset($input['state']) ? ImageState::create($input['state']) : null;
        $this->imageRecipe = isset($input['imageRecipe']) ? ImageRecipe::create($input['imageRecipe']) : null;
        $this->containerRecipe = isset($input['containerRecipe']) ? ContainerRecipe::create($input['containerRecipe']) : null;
        $this->sourcePipelineName = $input['sourcePipelineName'] ?? null;
        $this->sourcePipelineArn = $input['sourcePipelineArn'] ?? null;
        $this->infrastructureConfiguration = isset($input['infrastructureConfiguration']) ? InfrastructureConfiguration::create($input['infrastructureConfiguration']) : null;
        $this->distributionConfiguration = isset($input['distributionConfiguration']) ? DistributionConfiguration::create($input['distributionConfiguration']) : null;
        $this->imageTestsConfiguration = isset($input['imageTestsConfiguration']) ? ImageTestsConfiguration::create($input['imageTestsConfiguration']) : null;
        $this->dateCreated = $input['dateCreated'] ?? null;
        $this->outputResources = isset($input['outputResources']) ? OutputResources::create($input['outputResources']) : null;
        $this->tags = $input['tags'] ?? null;
        $this->buildType = $input['buildType'] ?? null;
        $this->imageSource = $input['imageSource'] ?? null;
        $this->scanState = isset($input['scanState']) ? ImageScanState::create($input['scanState']) : null;
        $this->imageScanningConfiguration = isset($input['imageScanningConfiguration']) ? ImageScanningConfiguration::create($input['imageScanningConfiguration']) : null;
        $this->deprecationTime = $input['deprecationTime'] ?? null;
        $this->lifecycleExecutionId = $input['lifecycleExecutionId'] ?? null;
        $this->executionRole = $input['executionRole'] ?? null;
        $this->workflows = isset($input['workflows']) ? array_map([WorkflowConfiguration::class, 'create'], $input['workflows']) : null;
        $this->loggingConfiguration = isset($input['loggingConfiguration']) ? ImageLoggingConfiguration::create($input['loggingConfiguration']) : null;
    }

    /**
     * @param array{
     *   arn?: string|null,
     *   type?: ImageType::*|null,
     *   name?: string|null,
     *   version?: string|null,
     *   platform?: Platform::*|null,
     *   enhancedImageMetadataEnabled?: bool|null,
     *   osVersion?: string|null,
     *   state?: ImageState|array|null,
     *   imageRecipe?: ImageRecipe|array|null,
     *   containerRecipe?: ContainerRecipe|array|null,
     *   sourcePipelineName?: string|null,
     *   sourcePipelineArn?: string|null,
     *   infrastructureConfiguration?: InfrastructureConfiguration|array|null,
     *   distributionConfiguration?: DistributionConfiguration|array|null,
     *   imageTestsConfiguration?: ImageTestsConfiguration|array|null,
     *   dateCreated?: string|null,
     *   outputResources?: OutputResources|array|null,
     *   tags?: array<string, string>|null,
     *   buildType?: BuildType::*|null,
     *   imageSource?: ImageSource::*|null,
     *   scanState?: ImageScanState|array|null,
     *   imageScanningConfiguration?: ImageScanningConfiguration|array|null,
     *   deprecationTime?: \DateTimeImmutable|null,
     *   lifecycleExecutionId?: string|null,
     *   executionRole?: string|null,
     *   workflows?: array<WorkflowConfiguration|array>|null,
     *   loggingConfiguration?: ImageLoggingConfiguration|array|null,
     * }|Image $input
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

    public function getContainerRecipe(): ?ContainerRecipe
    {
        return $this->containerRecipe;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function getDeprecationTime(): ?\DateTimeImmutable
    {
        return $this->deprecationTime;
    }

    public function getDistributionConfiguration(): ?DistributionConfiguration
    {
        return $this->distributionConfiguration;
    }

    public function getEnhancedImageMetadataEnabled(): ?bool
    {
        return $this->enhancedImageMetadataEnabled;
    }

    public function getExecutionRole(): ?string
    {
        return $this->executionRole;
    }

    public function getImageRecipe(): ?ImageRecipe
    {
        return $this->imageRecipe;
    }

    public function getImageScanningConfiguration(): ?ImageScanningConfiguration
    {
        return $this->imageScanningConfiguration;
    }

    /**
     * @return ImageSource::*|null
     */
    public function getImageSource(): ?string
    {
        return $this->imageSource;
    }

    public function getImageTestsConfiguration(): ?ImageTestsConfiguration
    {
        return $this->imageTestsConfiguration;
    }

    public function getInfrastructureConfiguration(): ?InfrastructureConfiguration
    {
        return $this->infrastructureConfiguration;
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

    /**
     * @return Platform::*|null
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function getScanState(): ?ImageScanState
    {
        return $this->scanState;
    }

    public function getSourcePipelineArn(): ?string
    {
        return $this->sourcePipelineArn;
    }

    public function getSourcePipelineName(): ?string
    {
        return $this->sourcePipelineName;
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

    /**
     * @return WorkflowConfiguration[]
     */
    public function getWorkflows(): array
    {
        return $this->workflows ?? [];
    }
}
