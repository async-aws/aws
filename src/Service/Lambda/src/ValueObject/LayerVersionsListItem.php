<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\Architecture;
use AsyncAws\Lambda\Enum\Runtime;

/**
 * Details about a version of an Lambda layer.
 *
 * @see https://docs.aws.amazon.com/lambda/latest/dg/configuration-layers.html
 */
final class LayerVersionsListItem
{
    /**
     * The ARN of the layer version.
     */
    private $layerVersionArn;

    /**
     * The version number.
     */
    private $version;

    /**
     * The description of the version.
     */
    private $description;

    /**
     * The date that the version was created, in ISO 8601 format. For example, `2018-11-27T15:10:45.123+0000`.
     */
    private $createdDate;

    /**
     * The layer's compatible runtimes.
     */
    private $compatibleRuntimes;

    /**
     * The layer's open-source license.
     */
    private $licenseInfo;

    /**
     * A list of compatible instruction set architectures.
     *
     * @see https://docs.aws.amazon.com/lambda/latest/dg/foundation-arch.html
     */
    private $compatibleArchitectures;

    /**
     * @param array{
     *   LayerVersionArn?: null|string,
     *   Version?: null|string,
     *   Description?: null|string,
     *   CreatedDate?: null|string,
     *   CompatibleRuntimes?: null|list<Runtime::*>,
     *   LicenseInfo?: null|string,
     *   CompatibleArchitectures?: null|list<Architecture::*>,
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

    public function getVersion(): ?string
    {
        return $this->version;
    }
}
