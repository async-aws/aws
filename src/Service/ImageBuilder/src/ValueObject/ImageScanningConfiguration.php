<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Contains settings for Image Builder image resource and container image scans.
 */
final class ImageScanningConfiguration
{
    /**
     * Specifies whether Amazon Inspector scans for vulnerabilities when you create a new image, and whether Image Builder
     * saves the findings. Amazon Inspector must be enabled in the account. Image tests must also be enabled. For AMI
     * output, Amazon Inspector scans the test instance. For container output, Amazon Inspector scans the container image
     * that Image Builder pushes to the Amazon ECR repository from your `ecrConfiguration` settings.
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
