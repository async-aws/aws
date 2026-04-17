<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Define and configure the output AMIs of the pipeline.
 */
final class AmiDistributionConfiguration
{
    /**
     * The name of the output AMI.
     *
     * @var string|null
     */
    private $name;

    /**
     * The description of the AMI distribution configuration. Minimum and maximum length are in characters.
     *
     * @var string|null
     */
    private $description;

    /**
     * The ID of an account to which you want to distribute an image.
     *
     * @var string[]|null
     */
    private $targetAccountIds;

    /**
     * The tags to apply to AMIs distributed to this Region.
     *
     * @var array<string, string>|null
     */
    private $amiTags;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the KMS key used to encrypt the distributed image. This can
     * be either the Key ARN or the Alias ARN. For more information, see Key identifiers (KeyId) [^1] in the *Key Management
     * Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * Launch permissions can be used to configure which Amazon Web Services accounts can use the AMI to launch instances.
     *
     * @var LaunchPermissionConfiguration|null
     */
    private $launchPermission;

    /**
     * @param array{
     *   name?: string|null,
     *   description?: string|null,
     *   targetAccountIds?: string[]|null,
     *   amiTags?: array<string, string>|null,
     *   kmsKeyId?: string|null,
     *   launchPermission?: LaunchPermissionConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->description = $input['description'] ?? null;
        $this->targetAccountIds = $input['targetAccountIds'] ?? null;
        $this->amiTags = $input['amiTags'] ?? null;
        $this->kmsKeyId = $input['kmsKeyId'] ?? null;
        $this->launchPermission = isset($input['launchPermission']) ? LaunchPermissionConfiguration::create($input['launchPermission']) : null;
    }

    /**
     * @param array{
     *   name?: string|null,
     *   description?: string|null,
     *   targetAccountIds?: string[]|null,
     *   amiTags?: array<string, string>|null,
     *   kmsKeyId?: string|null,
     *   launchPermission?: LaunchPermissionConfiguration|array|null,
     * }|AmiDistributionConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAmiTags(): array
    {
        return $this->amiTags ?? [];
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getLaunchPermission(): ?LaunchPermissionConfiguration
    {
        return $this->launchPermission;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getTargetAccountIds(): array
    {
        return $this->targetAccountIds ?? [];
    }
}
