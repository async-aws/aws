<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\ImageScanningType;

/**
 * Contains settings for Image Builder image resource and container image scans.
 */
final class ImageScanningConfiguration
{
    /**
     * Specifies whether Amazon Inspector scans for vulnerabilities when you create a new image. You must also enable image
     * tests. For AMI output, `imageScanningTypes` sets whether Amazon Inspector scans the test instance or the output AMI.
     * For container output, Amazon Inspector scans the container image that Image Builder pushes to the Amazon ECR
     * repository from your `ecrConfiguration` settings.
     *
     * To use container image scans, you must activate Amazon Inspector for Amazon ECR scanning in your account. The Image
     * Builder `ListImageScanFindings` operation returns the findings of these scans.
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
     * The type of vulnerability scan that Image Builder runs for AMI output when `imageScanningEnabled` is `true`. Specify
     * a single value. If you omit `imageScanningTypes`, Image Builder uses `TEST_INSTANCE_CVE`. The scan types work as
     * follows:
     *
     * - With `TEST_INSTANCE_CVE`, Amazon Inspector scans the test instance during image tests. To use this scan, you must
     *   activate Amazon Inspector for Amazon EC2 scanning in your account. The Image Builder `ListImageScanFindings`
     *   operation returns the findings.
     * - With `ON_DEMAND_AMI_CVE`, Amazon Inspector starts an on-demand scan of the output AMI during image tests. To use
     *   this scan, you must build from an image recipe, but you do not need to activate Amazon Inspector in your account.
     *   The Amazon EC2 GetImageFindings [^1] operation returns the findings.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_GetImageFindings.html
     *
     * @var list<ImageScanningType::*>|null
     */
    private $imageScanningTypes;

    /**
     * The condition that the output AMI's scan results must meet for the image to pass. You can set `scanResultCondition`
     * only when `imageScanningTypes` is `ON_DEMAND_AMI_CVE`. If you omit it, Image Builder starts the scan but does not
     * wait for it or use its results to pass or fail the image.
     *
     * When you set `scanResultCondition`, the test phase waits for the scan to finish. Then Image Builder checks the
     * findings against the condition. By default, Image Builder counts only findings that have a fix available. To count
     * every finding, set `fixAvailable` to `false`. If a counted finding exceeds `maximumSeverityLabel`, Image Builder
     * fails the image in the test phase and does not distribute the AMI.
     *
     * @var ScanResultCondition|null
     */
    private $scanResultCondition;

    /**
     * @param array{
     *   imageScanningEnabled?: bool|null,
     *   ecrConfiguration?: EcrConfiguration|array|null,
     *   imageScanningTypes?: array<ImageScanningType::*>|null,
     *   scanResultCondition?: ScanResultCondition|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->imageScanningEnabled = $input['imageScanningEnabled'] ?? null;
        $this->ecrConfiguration = isset($input['ecrConfiguration']) ? EcrConfiguration::create($input['ecrConfiguration']) : null;
        $this->imageScanningTypes = $input['imageScanningTypes'] ?? null;
        $this->scanResultCondition = isset($input['scanResultCondition']) ? ScanResultCondition::create($input['scanResultCondition']) : null;
    }

    /**
     * @param array{
     *   imageScanningEnabled?: bool|null,
     *   ecrConfiguration?: EcrConfiguration|array|null,
     *   imageScanningTypes?: array<ImageScanningType::*>|null,
     *   scanResultCondition?: ScanResultCondition|array|null,
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

    /**
     * @return list<ImageScanningType::*>
     */
    public function getImageScanningTypes(): array
    {
        return $this->imageScanningTypes ?? [];
    }

    public function getScanResultCondition(): ?ScanResultCondition
    {
        return $this->scanResultCondition;
    }
}
