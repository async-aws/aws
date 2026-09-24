<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Contains settings for the Systems Manager agent on your build instance. This setting applies to Linux and macOS build
 * instances only. Requests that set it for a recipe with a Windows base image are rejected.
 */
final class SystemsManagerAgent
{
    /**
     * Specifies whether the Systems Manager agent is removed from your final build image before Image Builder creates the
     * new AMI. If `true`, the agent is removed. If `false`, the agent is kept, so that it's included in the AMI. If you
     * don't set this property, Image Builder removes the agent only if Image Builder installed the agent during the build.
     * An agent that was pre-installed on the base image is kept.
     *
     * @var bool|null
     */
    private $uninstallAfterBuild;

    /**
     * @param array{
     *   uninstallAfterBuild?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->uninstallAfterBuild = $input['uninstallAfterBuild'] ?? null;
    }

    /**
     * @param array{
     *   uninstallAfterBuild?: bool|null,
     * }|SystemsManagerAgent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getUninstallAfterBuild(): ?bool
    {
        return $this->uninstallAfterBuild;
    }
}
