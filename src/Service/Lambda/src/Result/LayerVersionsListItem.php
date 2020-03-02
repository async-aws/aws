<?php

namespace AsyncAws\Lambda\Result;

class LayerVersionsListItem
{
    private $LayerVersionArn;

    private $Version;

    private $Description;

    private $CreatedDate;

    private $CompatibleRuntimes = [];

    private $LicenseInfo;

    /**
     * @param array{
     *   LayerVersionArn: ?string,
     *   Version: ?string,
     *   Description: ?string,
     *   CreatedDate: ?string,
     *   CompatibleRuntimes: ?string[],
     *   LicenseInfo: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->LayerVersionArn = $input['LayerVersionArn'];
        $this->Version = $input['Version'];
        $this->Description = $input['Description'];
        $this->CreatedDate = $input['CreatedDate'];
        $this->CompatibleRuntimes = $input['CompatibleRuntimes'] ?? [];
        $this->LicenseInfo = $input['LicenseInfo'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getCompatibleRuntimes(): array
    {
        return $this->CompatibleRuntimes;
    }

    /**
     * The date that the version was created, in ISO 8601 format. For example, `2018-11-27T15:10:45.123+0000`.
     */
    public function getCreatedDate(): ?string
    {
        return $this->CreatedDate;
    }

    /**
     * The description of the version.
     */
    public function getDescription(): ?string
    {
        return $this->Description;
    }

    /**
     * The ARN of the layer version.
     */
    public function getLayerVersionArn(): ?string
    {
        return $this->LayerVersionArn;
    }

    /**
     * The layer's open-source license.
     */
    public function getLicenseInfo(): ?string
    {
        return $this->LicenseInfo;
    }

    /**
     * The version number.
     */
    public function getVersion(): ?string
    {
        return $this->Version;
    }
}
