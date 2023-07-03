<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\Runtime;

/**
 * Details about a version of an Lambda layer [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
 */
final class LayerVersionsListItem
{
    /**
     * The ARN of the layer version.
     *
     * @var string|null
     */
    private $layerVersionArn;

    /**
     * The version number.
     *
     * @var int|null
     */
    private $version;

    /**
     * The description of the version.
     *
     * @var string|null
     */
    private $description;

    /**
     * The date that the version was created, in ISO 8601 format. For example, `2018-11-27T15:10:45.123+0000`.
     *
     * @var string|null
     */
    private $createdDate;

    /**
     * The layer's compatible runtimes.
     *
     * The following list includes deprecated runtimes. For more information, see Runtime deprecation policy [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/lambda-runtimes.html#runtime-support-policy
     *
     * @var list<Runtime::*>|null
     */
    private $compatibleRuntimes;

    /**
     * The layer's open-source license.
     *
     * @var string|null
     */
    private $licenseInfo;

    /**
     * A list of compatible instruction set architectures [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/foundation-arch.html
     *
     * @var list<Architecture::*>|null
     */
    private $compatibleArchitectures;

    /**
     * @param array{
     *   LayerVersionArn?: null|string,
     *   Version?: null|int,
     *   Description?: null|string,
     *   CreatedDate?: null|string,
     *   CompatibleRuntimes?: null|array<Runtime::*>,
     *   LicenseInfo?: null|string,
     *   CompatibleArchitectures?: null|array<Architecture::*>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->layerVersionArn = $input['LayerVersionArn'] ?? null;
        $this->version = $input['Version'] ?? null;
        $this->description = $input['Description'] ?? null;
        $this->createdDate = $input['CreatedDate'] ?? null;
        $this->compatibleRuntimes = $input['CompatibleRuntimes'] ?? null;
        $this->licenseInfo = $input['LicenseInfo'] ?? null;
        $this->compatibleArchitectures = $input['CompatibleArchitectures'] ?? null;
    }

    /**
     * @param array{
     *   LayerVersionArn?: null|string,
     *   Version?: null|int,
     *   Description?: null|string,
     *   CreatedDate?: null|string,
     *   CompatibleRuntimes?: null|array<Runtime::*>,
     *   LicenseInfo?: null|string,
     *   CompatibleArchitectures?: null|array<Architecture::*>,
     * }|LayerVersionsListItem $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Architecture::*>
     */
    public function getCompatibleArchitectures(): array
    {
        return $this->compatibleArchitectures ?? [];
    }

    /**
     * @return list<Runtime::*>
     */
    public function getCompatibleRuntimes(): array
    {
        return $this->compatibleRuntimes ?? [];
    }

    public function getCreatedDate(): ?string
    {
        return $this->createdDate;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getLayerVersionArn(): ?string
    {
        return $this->layerVersionArn;
    }

    public function getLicenseInfo(): ?string
    {
        return $this->licenseInfo;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }
}
