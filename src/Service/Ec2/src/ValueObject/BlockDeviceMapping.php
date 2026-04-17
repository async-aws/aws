<?php

namespace AsyncAws\Ec2\ValueObject;

/**
 * Describes a block device mapping, which defines the EBS volumes and instance store volumes to attach to an instance
 * at launch.
 */
final class BlockDeviceMapping
{
    /**
     * Parameters used to automatically set up EBS volumes when the instance is launched.
     *
     * @var EbsBlockDevice|null
     */
    private $ebs;

    /**
     * To omit the device from the block device mapping, specify an empty string. When this property is specified, the
     * device is removed from the block device mapping regardless of the assigned value.
     *
     * @var string|null
     */
    private $noDevice;

    /**
     * The device name. For available device names, see Device names for volumes [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/device_naming.html
     *
     * @var string|null
     */
    private $deviceName;

    /**
     * The virtual device name (`ephemeral`N). Instance store volumes are numbered starting from 0. An instance type with 2
     * available instance store volumes can specify mappings for `ephemeral0` and `ephemeral1`. The number of available
     * instance store volumes depends on the instance type. After you connect to the instance, you must mount the volume.
     *
     * NVMe instance store volumes are automatically enumerated and assigned a device name. Including them in your block
     * device mapping has no effect.
     *
     * Constraints: For M3 instances, you must specify instance store volumes in the block device mapping for the instance.
     * When you launch an M3 instance, we ignore any instance store volumes specified in the block device mapping for the
     * AMI.
     *
     * @var string|null
     */
    private $virtualName;

    /**
     * @param array{
     *   Ebs?: EbsBlockDevice|array|null,
     *   NoDevice?: string|null,
     *   DeviceName?: string|null,
     *   VirtualName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ebs = isset($input['Ebs']) ? EbsBlockDevice::create($input['Ebs']) : null;
        $this->noDevice = $input['NoDevice'] ?? null;
        $this->deviceName = $input['DeviceName'] ?? null;
        $this->virtualName = $input['VirtualName'] ?? null;
    }

    /**
     * @param array{
     *   Ebs?: EbsBlockDevice|array|null,
     *   NoDevice?: string|null,
     *   DeviceName?: string|null,
     *   VirtualName?: string|null,
     * }|BlockDeviceMapping $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeviceName(): ?string
    {
        return $this->deviceName;
    }

    public function getEbs(): ?EbsBlockDevice
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
