<?php

namespace AsyncAws\ImageBuilder\ValueObject;

/**
 * Defines block device mappings for the instance used to configure your image.
 */
final class InstanceBlockDeviceMapping
{
    /**
     * The device to which these mappings apply.
     *
     * @var string|null
     */
    private $deviceName;

    /**
     * Use to manage Amazon EBS-specific configuration for this mapping.
     *
     * @var EbsInstanceBlockDeviceSpecification|null
     */
    private $ebs;

    /**
     * Use to manage instance ephemeral devices.
     *
     * @var string|null
     */
    private $virtualName;

    /**
     * Use to remove a mapping from the base image.
     *
     * @var string|null
     */
    private $noDevice;

    /**
     * @param array{
     *   deviceName?: string|null,
     *   ebs?: EbsInstanceBlockDeviceSpecification|array|null,
     *   virtualName?: string|null,
     *   noDevice?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deviceName = $input['deviceName'] ?? null;
        $this->ebs = isset($input['ebs']) ? EbsInstanceBlockDeviceSpecification::create($input['ebs']) : null;
        $this->virtualName = $input['virtualName'] ?? null;
        $this->noDevice = $input['noDevice'] ?? null;
    }

    /**
     * @param array{
     *   deviceName?: string|null,
     *   ebs?: EbsInstanceBlockDeviceSpecification|array|null,
     *   virtualName?: string|null,
     *   noDevice?: string|null,
     * }|InstanceBlockDeviceMapping $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeviceName(): ?string
    {
        return $this->deviceName;
    }

    public function getEbs(): ?EbsInstanceBlockDeviceSpecification
    {
        return $this->ebs;
    }

    public function getNoDevice(): ?string
    {
        return $this->noDevice;
    }

    public function getVirtualName(): ?string
    {
        return $this->virtualName;
    }
}
