<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Contains settings for the Systems Manager agent on your build instance.
 */
final class SystemsManagerAgent
{
    /**
     * Controls whether the Systems Manager agent is removed from your final build image, prior to creating the new AMI. If
     * this is set to true, then the agent is removed from the final image. If it's set to false, then the agent is left in,
     * so that it is included in the new AMI. default value is false.
     *
     * The default behavior of uninstallAfterBuild is to remove the SSM Agent if it was installed by EC2 Image Builder
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
