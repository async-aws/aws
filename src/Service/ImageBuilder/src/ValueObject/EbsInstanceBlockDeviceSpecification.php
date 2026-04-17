<?php

namespace AsyncAws\ImageBuilder\ValueObject;

use AsyncAws\ImageBuilder\Enum\EbsVolumeType;

/**
 * Amazon EBS-specific block device mapping specifications.
 */
final class EbsInstanceBlockDeviceSpecification
{
    /**
     * Use to configure device encryption.
     *
     * @var bool|null
     */
    private $encrypted;

    /**
     * Use to configure delete on termination of the associated device.
     *
     * @var bool|null
     */
    private $deleteOnTermination;

    /**
     * Use to configure device IOPS.
     *
     * @var int|null
     */
    private $iops;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the KMS key to use when encrypting the device. This can be
     * either the Key ARN or the Alias ARN. For more information, see Key identifiers (KeyId) [^1] in the *Key Management
     * Service Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/kms/latest/developerguide/concepts.html#key-id-key-ARN
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The snapshot that defines the device contents.
     *
     * @var string|null
     */
    private $snapshotId;

    /**
     * Use to override the device's volume size.
     *
     * @var int|null
     */
    private $volumeSize;

    /**
     * Use to override the device's volume type.
     *
     * @var EbsVolumeType::*|null
     */
    private $volumeType;

    /**
     * **For GP3 volumes only** – The throughput in MiB/s that the volume supports.
     *
     * @var int|null
     */
    private $throughput;

    /**
     * @param array{
     *   encrypted?: bool|null,
     *   deleteOnTermination?: bool|null,
     *   iops?: int|null,
     *   kmsKeyId?: string|null,
     *   snapshotId?: string|null,
     *   volumeSize?: int|null,
     *   volumeType?: EbsVolumeType::*|null,
     *   throughput?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->encrypted = $input['encrypted'] ?? null;
        $this->deleteOnTermination = $input['deleteOnTermination'] ?? null;
        $this->iops = $input['iops'] ?? null;
        $this->kmsKeyId = $input['kmsKeyId'] ?? null;
        $this->snapshotId = $input['snapshotId'] ?? null;
        $this->volumeSize = $input['volumeSize'] ?? null;
        $this->volumeType = $input['volumeType'] ?? null;
        $this->throughput = $input['throughput'] ?? null;
    }

    /**
     * @param array{
     *   encrypted?: bool|null,
     *   deleteOnTermination?: bool|null,
     *   iops?: int|null,
     *   kmsKeyId?: string|null,
     *   snapshotId?: string|null,
     *   volumeSize?: int|null,
     *   volumeType?: EbsVolumeType::*|null,
     *   throughput?: int|null,
     * }|EbsInstanceBlockDeviceSpecification $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeleteOnTermination(): ?bool
    {
        return $this->deleteOnTermination;
    }

    public function getEncrypted(): ?bool
    {
        return $this->encrypted;
    }

    public function getIops(): ?int
    {
        return $this->iops;
    }

    public function getKmsKeyId(): ?string
    {
        return $this->kmsKeyId;
    }

    public function getSnapshotId(): ?string
    {
        return $this->snapshotId;
    }

    public function getThroughput(): ?int
    {
        return $this->throughput;
    }

    public function getVolumeSize(): ?int
    {
        return $this->volumeSize;
    }

    /**
     * @return EbsVolumeType::*|null
     */
    public function getVolumeType(): ?string
    {
        return $this->volumeType;
    }
}
