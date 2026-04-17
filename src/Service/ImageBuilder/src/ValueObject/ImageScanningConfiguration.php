<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Contains settings for Image Builder image resource and container image scans.
 */
final class ImageScanningConfiguration
{
    /**
     * A setting that indicates whether Image Builder keeps a snapshot of the vulnerability scans that Amazon Inspector runs
     * against the build instance when you create a new image.
     *
     * @var bool|null
     */
    private $imageScanningEnabled;

    /**
     * Contains Amazon ECR settings for vulnerability scans.
     *
     * @var EcrConfiguration|null
     */
    private $ecrConfiguration;

    /**
     * @param array{
     *   imageScanningEnabled?: bool|null,
     *   ecrConfiguration?: EcrConfiguration|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->imageScanningEnabled = $input['imageScanningEnabled'] ?? null;
        $this->ecrConfiguration = isset($input['ecrConfiguration']) ? EcrConfiguration::create($input['ecrConfiguration']) : null;
    }

    /**
     * @param array{
     *   imageScanningEnabled?: bool|null,
     *   ecrConfiguration?: EcrConfiguration|array|null,
     * }|ImageScanningConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEcrConfiguration(): ?EcrConfiguration
    {
        return $this->ecrConfiguration;
    }

    public function getImageScanningEnabled(): ?bool
    {
        return $this->imageScanningEnabled;
    }
}
