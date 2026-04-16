<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\ContainerType;
use AsyncAws\ImageBuilder\Enum\Platform;

/**
 * A container recipe.
 */
final class ContainerRecipe
{
    /**
     * The Amazon Resource Name (ARN) of the container recipe.
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
     * Specifies the type of container, such as Docker.
     *
     * @var ContainerType::*|null
     */
    private $containerType;

    /**
     * The name of the container recipe.
     *
     * @var string|null
     */
    private $name;

    /**
     * The description of the container recipe.
     *
     * @var string|null
     */
    private $description;

    /**
     * The system platform for the container, such as Windows or Linux.
     *
     * @var Platform::*|null
     */
    private $platform;

    /**
     * The owner of the container recipe.
     *
     * @var string|null
     */
    private $owner;

    /**
     * The semantic version of the container recipe.
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
     * Build and test components that are included in the container recipe. Recipes require a minimum of one build
     * component, and can have a maximum of 20 build and test components in any combination.
     *
     * @var ComponentConfiguration[]|null
     */
    private $components;

    /**
     * A group of options that can be used to configure an instance for building and testing container images.
     *
     * @var InstanceConfiguration|null
     */
    private $instanceConfiguration;

    /**
     * Dockerfiles are text documents that are used to build Docker containers, and ensure that they contain all of the
     * elements required by the application running inside. The template data consists of contextual variables where Image
     * Builder places build information or scripts, based on your container image recipe.
     *
     * @var string|null
     */
    private $dockerfileTemplateData;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies which KMS key is used to encrypt the container image for
     * distribution to the target Region. This can be either the Key ARN or the Alias ARN. For more information, see Key
     * identifiers (KeyId) [^1] in the *Key Management Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * A flag that indicates if the target container is encrypted.
     *
     * @var bool|null
     */
    private $encrypted;

    /**
     * The base image for customizations specified in the container recipe. This can contain an Image Builder image resource
     * ARN or a container image URI, for example `amazonlinux:latest`.
     *
     * @var string|null
     */
    private $parentImage;

    /**
     * The date when this container recipe was created.
     *
     * @var string|null
     */
    private $dateCreated;

    /**
     * Tags that are attached to the container recipe.
     *
     * @var array<string, string>|null
     */
    private $tags;

    /**
     * The working directory for use during build and test workflows.
     *
     * @var string|null
     */
    private $workingDirectory;

    /**
     * The destination repository for the container image.
     *
     * @var TargetContainerRepository|null
     */
    private $targetRepository;

    /**
     * @param array{
     *   arn?: string|null,
     *   containerType?: ContainerType::*|null,
     *   name?: string|null,
     *   description?: string|null,
     *   platform?: Platform::*|null,
     *   owner?: string|null,
     *   version?: string|null,
     *   components?: array<ComponentConfiguration|array>|null,
     *   instanceConfiguration?: InstanceConfiguration|array|null,
     *   dockerfileTemplateData?: string|null,
     *   kmsKeyId?: string|null,
     *   encrypted?: bool|null,
     *   parentImage?: string|null,
     *   dateCreated?: string|null,
     *   tags?: array<string, string>|null,
     *   workingDirectory?: string|null,
     *   targetRepository?: TargetContainerRepository|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->arn = $input['arn'] ?? null;
        $this->containerType = $input['containerType'] ?? null;
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->platform = $input['platform'] ?? null;
        $this->owner = $input['owner'] ?? null;
        $this->version = $input['version'] ?? null;
        $this->components = isset($input['components']) ? array_map([ComponentConfiguration::class, 'create'], $input['components']) : null;
        $this->instanceConfiguration = isset($input['instanceConfiguration']) ? InstanceConfiguration::create($input['instanceConfiguration']) : null;
        $this->dockerfileTemplateData = $input['dockerfileTemplateData'] ?? null;
        $this->kmsKeyId = $input['kmsKeyId'] ?? null;
        $this->encrypted = $input['encrypted'] ?? null;
        $this->parentImage = $input['parentImage'] ?? null;
        $this->dateCreated = $input['dateCreated'] ?? null;
        $this->tags = $input['tags'] ?? null;
        $this->workingDirectory = $input['workingDirectory'] ?? null;
        $this->targetRepository = isset($input['targetRepository']) ? TargetContainerRepository::create($input['targetRepository']) : null;
    }

    /**
     * @param array{
     *   arn?: string|null,
     *   containerType?: ContainerType::*|null,
     *   name?: string|null,
     *   description?: string|null,
     *   platform?: Platform::*|null,
     *   owner?: string|null,
     *   version?: string|null,
     *   components?: array<ComponentConfiguration|array>|null,
     *   instanceConfiguration?: InstanceConfiguration|array|null,
     *   dockerfileTemplateData?: string|null,
     *   kmsKeyId?: string|null,
     *   encrypted?: bool|null,
     *   parentImage?: string|null,
     *   dateCreated?: string|null,
     *   tags?: array<string, string>|null,
     *   workingDirectory?: string|null,
     *   targetRepository?: TargetContainerRepository|array|null,
     * }|ContainerRecipe $input
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
     * @return ComponentConfiguration[]
     */
    public function getComponents(): array
    {
        return $this->components ?? [];
    }

    /**
     * @return ContainerType::*|null
     */
    public function getContainerType(): ?string
    {
        return $this->containerType;
    }

    public function getDateCreated(): ?string
    {
        return $this->dateCreated;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDockerfileTemplateData(): ?string
    {
        return $this->dockerfileTemplateData;
    }

    public function getEncrypted(): ?bool
    {
        return $this->encrypted;
    }

    public function getInstanceConfiguration(): ?InstanceConfiguration
    {
        return $this->instanceConfiguration;
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
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

    public function getTargetRepository(): ?TargetContainerRepository
    {
        return $this->targetRepository;
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
