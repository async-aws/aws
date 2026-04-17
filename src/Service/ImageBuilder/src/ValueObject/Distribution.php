<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Defines the settings for a specific Region.
 */
final class Distribution
{
    /**
     * The target Region.
     *
     * @var string
     */
    private $region;

    /**
     * The specific AMI settings; for example, launch permissions or AMI tags.
     *
     * @var AmiDistributionConfiguration|null
     */
    private $amiDistributionConfiguration;

    /**
     * Container distribution settings for encryption, licensing, and sharing in a specific Region.
     *
     * @var ContainerDistributionConfiguration|null
     */
    private $containerDistributionConfiguration;

    /**
     * The License Manager Configuration to associate with the AMI in the specified Region.
     *
     * @var string[]|null
     */
    private $licenseConfigurationArns;

    /**
     * A group of launchTemplateConfiguration settings that apply to image distribution for specified accounts.
     *
     * @var LaunchTemplateConfiguration[]|null
     */
    private $launchTemplateConfigurations;

    /**
     * Configure export settings to deliver disk images created from your image build, using a file format that is
     * compatible with your VMs in that Region.
     *
     * @var S3ExportConfiguration|null
     */
    private $s3ExportConfiguration;

    /**
     * The Windows faster-launching configurations to use for AMI distribution.
     *
     * @var FastLaunchConfiguration[]|null
     */
    private $fastLaunchConfigurations;

    /**
     * Contains settings to update Amazon Web Services Systems Manager (SSM) Parameter Store Parameters with output AMI IDs
     * from the build by target Region.
     *
     * @var SsmParameterConfiguration[]|null
     */
    private $ssmParameterConfigurations;

    /**
     * @param array{
     *   region: string,
     *   amiDistributionConfiguration?: AmiDistributionConfiguration|array|null,
     *   containerDistributionConfiguration?: ContainerDistributionConfiguration|array|null,
     *   licenseConfigurationArns?: string[]|null,
     *   launchTemplateConfigurations?: array<LaunchTemplateConfiguration|array>|null,
     *   s3ExportConfiguration?: S3ExportConfiguration|array|null,
     *   fastLaunchConfigurations?: array<FastLaunchConfiguration|array>|null,
     *   ssmParameterConfigurations?: array<SsmParameterConfiguration|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->region = $input['region'] ?? $this->throwException(new InvalidArgument('Missing required field "region".'));
        $this->amiDistributionConfiguration = isset($input['amiDistributionConfiguration']) ? AmiDistributionConfiguration::create($input['amiDistributionConfiguration']) : null;
        $this->containerDistributionConfiguration = isset($input['containerDistributionConfiguration']) ? ContainerDistributionConfiguration::create($input['containerDistributionConfiguration']) : null;
        $this->licenseConfigurationArns = $input['licenseConfigurationArns'] ?? null;
        $this->launchTemplateConfigurations = isset($input['launchTemplateConfigurations']) ? array_map([LaunchTemplateConfiguration::class, 'create'], $input['launchTemplateConfigurations']) : null;
        $this->s3ExportConfiguration = isset($input['s3ExportConfiguration']) ? S3ExportConfiguration::create($input['s3ExportConfiguration']) : null;
        $this->fastLaunchConfigurations = isset($input['fastLaunchConfigurations']) ? array_map([FastLaunchConfiguration::class, 'create'], $input['fastLaunchConfigurations']) : null;
        $this->ssmParameterConfigurations = isset($input['ssmParameterConfigurations']) ? array_map([SsmParameterConfiguration::class, 'create'], $input['ssmParameterConfigurations']) : null;
    }

    /**
     * @param array{
     *   region: string,
     *   amiDistributionConfiguration?: AmiDistributionConfiguration|array|null,
     *   containerDistributionConfiguration?: ContainerDistributionConfiguration|array|null,
     *   licenseConfigurationArns?: string[]|null,
     *   launchTemplateConfigurations?: array<LaunchTemplateConfiguration|array>|null,
     *   s3ExportConfiguration?: S3ExportConfiguration|array|null,
     *   fastLaunchConfigurations?: array<FastLaunchConfiguration|array>|null,
     *   ssmParameterConfigurations?: array<SsmParameterConfiguration|array>|null,
     * }|Distribution $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAmiDistributionConfiguration(): ?AmiDistributionConfiguration
    {
        return $this->amiDistributionConfiguration;
    }

    public function getContainerDistributionConfiguration(): ?ContainerDistributionConfiguration
    {
        return $this->containerDistributionConfiguration;
    }

    /**
     * @return FastLaunchConfiguration[]
     */
    public function getFastLaunchConfigurations(): array
    {
        return $this->fastLaunchConfigurations ?? [];
    }

    /**
     * @return LaunchTemplateConfiguration[]
     */
    public function getLaunchTemplateConfigurations(): array
    {
        return $this->launchTemplateConfigurations ?? [];
    }

    /**
     * @return string[]
     */
    public function getLicenseConfigurationArns(): array
    {
        return $this->licenseConfigurationArns ?? [];
    }

    public function getRegion(): string
    {
        return $this->region;
    }

    public function getS3ExportConfiguration(): ?S3ExportConfiguration
    {
        return $this->s3ExportConfiguration;
    }

    /**
     * @return SsmParameterConfiguration[]
     */
    public function getSsmParameterConfigurations(): array
    {
        return $this->ssmParameterConfigurations ?? [];
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
