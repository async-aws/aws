<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\BuildType;
use AsyncAws\ImageBuilder\Enum\ImageSource;
use AsyncAws\ImageBuilder\Enum\ImageType;
use AsyncAws\ImageBuilder\Enum\Platform;

/**
 * The defining characteristics of a specific version of an Image Builder image.
 */
final class ImageVersion
{
    /**
     * The Amazon Resource Name (ARN) of a specific version of an Image Builder image.
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
     * The name of this specific version of an Image Builder image.
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
     * Details for a specific version of an Image Builder image. This version follows the semantic version syntax.
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
     * The operating system platform of the image version, for example "Windows" or "Linux".
     *
     * @var Platform::*|null
     */
    private $platform;

    /**
     * The operating system version of the Amazon EC2 build instance. For example, Amazon Linux 2, Ubuntu 18, or Microsoft
     * Windows Server 2019.
     *
     * @var string|null
     */
    private $osVersion;

    /**
     * The owner of the image version.
     *
     * @var string|null
     */
    private $owner;

    /**
     * The date on which this specific version of the Image Builder image was created.
     *
     * @var string|null
     */
    private $dateCreated;

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
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   type?: ImageType::*|null,
     *   version?: string|null,
     *   platform?: Platform::*|null,
     *   osVersion?: string|null,
     *   owner?: string|null,
     *   dateCreated?: string|null,
     *   buildType?: BuildType::*|null,
     *   imageSource?: ImageSource::*|null,
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
        $this->owner = $input['owner'] ?? null;
        $this->dateCreated = $input['dateCreated'] ?? null;
        $this->buildType = $input['buildType'] ?? null;
        $this->imageSource = $input['imageSource'] ?? null;
    }

    /**
     * @param array{
     *   arn?: string|null,
     *   name?: string|null,
     *   type?: ImageType::*|null,
     *   version?: string|null,
     *   platform?: Platform::*|null,
     *   osVersion?: string|null,
     *   owner?: string|null,
     *   dateCreated?: string|null,
     *   buildType?: BuildType::*|null,
     *   imageSource?: ImageSource::*|null,
     * }|ImageVersion $input
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

    /**
     * @return ImageSource::*|null
     */
    public function getImageSource(): ?string
    {
        return $this->imageSource;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOsVersion(): ?string
    {
        return $this->osVersion;
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
