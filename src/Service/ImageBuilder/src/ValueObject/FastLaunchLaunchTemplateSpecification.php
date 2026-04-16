<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Identifies the launch template that the associated Windows AMI uses for launching an instance when faster launching
 * is enabled.
 *
 * > You can specify either the `launchTemplateName` or the `launchTemplateId`, but not both.
 */
final class FastLaunchLaunchTemplateSpecification
{
    /**
     * The ID of the launch template to use for faster launching for a Windows AMI.
     *
     * @var string|null
     */
    private $launchTemplateId;

    /**
     * The name of the launch template to use for faster launching for a Windows AMI.
     *
     * @var string|null
     */
    private $launchTemplateName;

    /**
     * The version of the launch template to use for faster launching for a Windows AMI.
     *
     * @var string|null
     */
    private $launchTemplateVersion;

    /**
     * @param array{
     *   launchTemplateId?: string|null,
     *   launchTemplateName?: string|null,
     *   launchTemplateVersion?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->launchTemplateId = $input['launchTemplateId'] ?? null;
        $this->launchTemplateName = $input['launchTemplateName'] ?? null;
        $this->launchTemplateVersion = $input['launchTemplateVersion'] ?? null;
    }

    /**
     * @param array{
     *   launchTemplateId?: string|null,
     *   launchTemplateName?: string|null,
     *   launchTemplateVersion?: string|null,
     * }|FastLaunchLaunchTemplateSpecification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getLaunchTemplateId(): ?string
    {
        return $this->launchTemplateId;
    }

    public function getLaunchTemplateName(): ?string
    {
        return $this->launchTemplateName;
    }

    public function getLaunchTemplateVersion(): ?string
    {
        return $this->launchTemplateVersion;
    }
}
