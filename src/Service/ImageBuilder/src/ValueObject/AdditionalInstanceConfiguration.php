<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * In addition to your infrastructure configuration, these settings provide an extra layer of control over your build
 * instances. You can also specify commands to run on launch for all of your build instances.
 *
 * Image Builder does not automatically install the Systems Manager agent on Windows instances. If your base image
 * includes the Systems Manager agent, then the AMI that you create will also include the agent. For Linux instances, if
 * the base image does not already include the Systems Manager agent, Image Builder installs it. For Linux instances
 * where Image Builder installs the Systems Manager agent, you can choose whether to keep it for the AMI that you
 * create.
 */
final class AdditionalInstanceConfiguration
{
    /**
     * Contains settings for the Systems Manager agent on your build instance.
     *
     * @var SystemsManagerAgent|null
     */
    private $systemsManagerAgent;

    /**
     * Use this property to provide commands or a command script to run when you launch your build instance.
     *
     * The userDataOverride property replaces any commands that Image Builder might have added to ensure that Systems
     * Manager is installed on your Linux build instance. If you override the user data, make sure that you add commands to
     * install Systems Manager, if it is not pre-installed on your base image.
     *
     * > The user data is always base 64 encoded. For example, the following commands are encoded as
     * > `IyEvYmluL2Jhc2gKbWtkaXIgLXAgL3Zhci9iYi8KdG91Y2ggL3Zhci$`:
     * >
     * > *#!/bin/bash*
     * >
     * > mkdir -p /var/bb/
     * >
     * > touch /var
     *
     * @var string|null
     */
    private $userDataOverride;

    /**
     * @param array{
     *   systemsManagerAgent?: SystemsManagerAgent|array|null,
     *   userDataOverride?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->systemsManagerAgent = isset($input['systemsManagerAgent']) ? SystemsManagerAgent::create($input['systemsManagerAgent']) : null;
        $this->userDataOverride = $input['userDataOverride'] ?? null;
    }

    /**
     * @param array{
     *   systemsManagerAgent?: SystemsManagerAgent|array|null,
     *   userDataOverride?: string|null,
     * }|AdditionalInstanceConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSystemsManagerAgent(): ?SystemsManagerAgent
    {
        return $this->systemsManagerAgent;
    }

    public function getUserDataOverride(): ?string
    {
        return $this->userDataOverride;
    }
}
