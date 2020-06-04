<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\Runtime;

final class LayerVersionsListItem
{
    /**
     * The ARN of the layer version.
     */
    private $LayerVersionArn;

    /**
     * The version number.
     */
    private $Version;

    /**
     * The description of the version.
     */
    private $Description;

    /**
     * The date that the version was created, in ISO 8601 format. For example, `2018-11-27T15:10:45.123+0000`.
     */
    private $CreatedDate;

    /**
     * The layer's compatible runtimes.
     */
    private $CompatibleRuntimes;

    /**
     * The layer's open-source license.
     */
    private $LicenseInfo;

    /**
     * @param array{
     *   LayerVersionArn?: null|string,
     *   Version?: null|string,
     *   Description?: null|string,
     *   CreatedDate?: null|string,
     *   CompatibleRuntimes?: null|list<\AsyncAws\Lambda\Enum\Runtime::*>,
     *   LicenseInfo?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->LayerVersionArn = $input['LayerVersionArn'] ?? null;
        $this->Version = $input['Version'] ?? null;
        $this->Description = $input['Description'] ?? null;
        $this->CreatedDate = $input['CreatedDate'] ?? null;
        $this->CompatibleRuntimes = $input['CompatibleRuntimes'] ?? null;
        $this->LicenseInfo = $input['LicenseInfo'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<Runtime::*>
     */
    public function getCompatibleRuntimes(): array
    {
        return $this->CompatibleRuntimes ?? [];
    }

    public function getCreatedDate(): ?string
    {
        return $this->CreatedDate;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function getLayerVersionArn(): ?string
    {
        return $this->LayerVersionArn;
    }

    public function getLicenseInfo(): ?string
    {
        return $this->LicenseInfo;
    }

    public function getVersion(): ?string
    {
        return $this->Version;
    }
}
