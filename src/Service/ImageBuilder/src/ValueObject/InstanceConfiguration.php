<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Defines a custom base AMI and block device mapping configurations of an instance used for building and testing
 * container images.
 */
final class InstanceConfiguration
{
    /**
     * The base image for a container build and test instance. This can contain an AMI ID or it can specify an Amazon Web
     * Services Systems Manager (SSM) Parameter Store Parameter, prefixed by `ssm:`, followed by the parameter name or ARN.
     *
     * If not specified, Image Builder uses the appropriate ECS-optimized AMI as a base image.
     *
     * @var string|null
     */
    private $image;

    /**
     * Defines the block devices to attach for building an instance from this Image Builder AMI.
     *
     * @var InstanceBlockDeviceMapping[]|null
     */
    private $blockDeviceMappings;

    /**
     * @param array{
     *   image?: string|null,
     *   blockDeviceMappings?: array<InstanceBlockDeviceMapping|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->image = $input['image'] ?? null;
        $this->blockDeviceMappings = isset($input['blockDeviceMappings']) ? array_map([InstanceBlockDeviceMapping::class, 'create'], $input['blockDeviceMappings']) : null;
    }

    /**
     * @param array{
     *   image?: string|null,
     *   blockDeviceMappings?: array<InstanceBlockDeviceMapping|array>|null,
     * }|InstanceConfiguration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InstanceBlockDeviceMapping[]
     */
    public function getBlockDeviceMappings(): array
    {
        return $this->blockDeviceMappings ?? [];
    }

    public function getImage(): ?string
    {
        return $this->image;
    }
}
