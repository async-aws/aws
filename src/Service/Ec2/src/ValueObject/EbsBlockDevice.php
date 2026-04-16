<?php

namespace AsyncAws\Ec2\ValueObject;

use AsyncAws\Ec2\Enum\VolumeType;

/**
 * Describes a block device for an EBS volume.
 */
final class EbsBlockDevice
{
    /**
     * Indicates whether the EBS volume is deleted on instance termination. For more information, see Preserving Amazon EBS
     * volumes on instance termination [^1] in the *Amazon EC2 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/terminating-instances.html#preserving-volumes-on-termination
     *
     * @var bool|null
     */
    private $deleteOnTermination;

    /**
     * The number of I/O operations per second (IOPS). For `gp3`, `io1`, and `io2` volumes, this represents the number of
     * IOPS that are provisioned for the volume. For `gp2` volumes, this represents the baseline performance of the volume
     * and the rate at which the volume accumulates I/O credits for bursting.
     *
     * The following are the supported values for each volume type:
     *
     * - `gp3`: 3,000 - 80,000 IOPS
     * - `io1`: 100 - 64,000 IOPS
     * - `io2`: 100 - 256,000 IOPS
     *
     * For `io2` volumes, you can achieve up to 256,000 IOPS on instances built on the Nitro System [^1]. On other
     * instances, you can achieve performance up to 32,000 IOPS.
     *
     * This parameter is required for `io1` and `io2` volumes. The default for `gp3` volumes is 3,000 IOPS.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/instance-types.html#ec2-nitro-instances
     *
     * @var int|null
     */
    private $iops;

    /**
     * The ID of the snapshot.
     *
     * @var string|null
     */
    private $snapshotId;

    /**
     * The size of the volume, in GiBs. You must specify either a snapshot ID or a volume size. If you specify a snapshot,
     * the default is the snapshot size. You can specify a volume size that is equal to or larger than the snapshot size.
     *
     * The following are the supported sizes for each volume type:
     *
     * - `gp2`: 1 - 16,384 GiB
     * - `gp3`: 1 - 65,536 GiB
     * - `io1`: 4 - 16,384 GiB
     * - `io2`: 4 - 65,536 GiB
     * - `st1` and `sc1`: 125 - 16,384 GiB
     * - `standard`: 1 - 1024 GiB
     *
     * @var int|null
     */
    private $volumeSize;

    /**
     * The volume type. For more information, see Amazon EBS volume types [^1] in the *Amazon EBS User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/ebs/latest/userguide/ebs-volume-types.html
     *
     * @var VolumeType::*|null
     */
    private $volumeType;

    /**
     * Identifier (key ID, key alias, key ARN, or alias ARN) of the customer managed KMS key to use for EBS encryption.
     *
     * This parameter is only supported on `BlockDeviceMapping` objects called by RunInstances [^1], RequestSpotFleet [^2],
     * and RequestSpotInstances [^3].
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RunInstances.html
     * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RequestSpotFleet.html
     * [^3]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RequestSpotInstances.html
     *
     * @var string|null
     */
    private $kmsKeyId;

    /**
     * The throughput that the volume supports, in MiB/s.
     *
     * This parameter is valid only for `gp3` volumes.
     *
     * Valid Range: Minimum value of 125. Maximum value of 2,000.
     *
     * @var int|null
     */
    private $throughput;

    /**
     * The ARN of the Outpost on which the snapshot is stored.
     *
     * This parameter is not supported when using CreateImage [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateImage.html
     *
     * @var string|null
     */
    private $outpostArn;

    /**
     * The Availability Zone where the EBS volume will be created (for example, `us-east-1a`).
     *
     * Either `AvailabilityZone` or `AvailabilityZoneId` can be specified, but not both. If neither is specified, Amazon EC2
     * automatically selects an Availability Zone within the Region.
     *
     * This parameter is not supported when using CreateFleet [^1], CreateImage [^2], DescribeImages [^3], RequestSpotFleet
     * [^4], RequestSpotInstances [^5], and RunInstances [^6].
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateFleet.html
     * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateImage.html
     * [^3]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeImages.html
     * [^4]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RequestSpotFleet.html
     * [^5]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RequestSpotInstances.html
     * [^6]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RunInstances.html
     *
     * @var string|null
     */
    private $availabilityZone;

    /**
     * Indicates whether the encryption state of an EBS volume is changed while being restored from a backing snapshot. The
     * effect of setting the encryption state to `true` depends on the volume origin (new or from a snapshot), starting
     * encryption state, ownership, and whether encryption by default is enabled. For more information, see Amazon EBS
     * encryption [^1] in the *Amazon EBS User Guide*.
     *
     * In no case can you remove encryption from an encrypted volume.
     *
     * Encrypted volumes can only be attached to instances that support Amazon EBS encryption. For more information, see
     * Supported instance types [^2].
     *
     * This parameter is not returned by DescribeImageAttribute.
     *
     * For CreateImage and RegisterImage, whether you can include this parameter, and the allowed values differ depending on
     * the type of block device mapping you are creating.
     *
     * - If you are creating a block device mapping for a **new (empty) volume**, you can include this parameter, and
     *   specify either `true` for an encrypted volume, or `false` for an unencrypted volume. If you omit this parameter, it
     *   defaults to `false` (unencrypted).
     * - If you are creating a block device mapping from an **existing encrypted or unencrypted snapshot**, you must omit
     *   this parameter. If you include this parameter, the request will fail, regardless of the value that you specify.
     * - If you are creating a block device mapping from an **existing unencrypted volume**, you can include this parameter,
     *   but you must specify `false`. If you specify `true`, the request will fail. In this case, we recommend that you
     *   omit the parameter.
     * - If you are creating a block device mapping from an **existing encrypted volume**, you can include this parameter,
     *   and specify either `true` or `false`. However, if you specify `false`, the parameter is ignored and the block
     *   device mapping is always encrypted. In this case, we recommend that you omit the parameter.
     *
     * [^1]: https://docs.aws.amazon.com/ebs/latest/userguide/ebs-encryption.html#encryption-parameters
     * [^2]: https://docs.aws.amazon.com/ebs/latest/userguide/ebs-encryption-requirements.html#ebs-encryption_supported_instances
     *
     * @var bool|null
     */
    private $encrypted;

    /**
     * Specifies the Amazon EBS Provisioned Rate for Volume Initialization (volume initialization rate), in MiB/s, at which
     * to download the snapshot blocks from Amazon S3 to the volume. This is also known as *volume initialization*.
     * Specifying a volume initialization rate ensures that the volume is initialized at a predictable and consistent rate
     * after creation. For more information, see Initialize Amazon EBS volumes [^1] in the *Amazon EC2 User Guide*.
     *
     * This parameter is supported only for volumes created from snapshots. Omit this parameter if:
     *
     * - You want to create the volume using fast snapshot restore. You must specify a snapshot that is enabled for fast
     *   snapshot restore. In this case, the volume is fully initialized at creation.
     *
     *   > If you specify a snapshot that is enabled for fast snapshot restore and a volume initialization rate, the volume
     *   > will be initialized at the specified rate instead of fast snapshot restore.
     *
     * - You want to create a volume that is initialized at the default rate.
     *
     * This parameter is not supported when using CreateImage [^2] and DescribeImages [^3].
     *
     * Valid range: 100 - 300 MiB/s
     *
     * [^1]: https://docs.aws.amazon.com/ebs/latest/userguide/initalize-volume.html
     * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateImage.html
     * [^3]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeImages.html
     *
     * @var int|null
     */
    private $volumeInitializationRate;

    /**
     * The ID of the Availability Zone where the EBS volume will be created (for example, `use1-az1`).
     *
     * Either `AvailabilityZone` or `AvailabilityZoneId` can be specified, but not both. If neither is specified, Amazon EC2
     * automatically selects an Availability Zone within the Region.
     *
     * This parameter is not supported when using CreateFleet [^1], CreateImage [^2], DescribeImages [^3], RequestSpotFleet
     * [^4], RequestSpotInstances [^5], and RunInstances [^6].
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateFleet.html
     * [^2]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateImage.html
     * [^3]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeImages.html
     * [^4]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RequestSpotFleet.html
     * [^5]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RequestSpotInstances.html
     * [^6]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_RunInstances.html
     *
     * @var string|null
     */
    private $availabilityZoneId;

    /**
     * The index of the EBS card. Some instance types support multiple EBS cards. The default EBS card index is 0.
     *
     * @var int|null
     */
    private $ebsCardIndex;

    /**
     * @param array{
     *   DeleteOnTermination?: bool|null,
     *   Iops?: int|null,
     *   SnapshotId?: string|null,
     *   VolumeSize?: int|null,
     *   VolumeType?: VolumeType::*|null,
     *   KmsKeyId?: string|null,
     *   Throughput?: int|null,
     *   OutpostArn?: string|null,
     *   AvailabilityZone?: string|null,
     *   Encrypted?: bool|null,
     *   VolumeInitializationRate?: int|null,
     *   AvailabilityZoneId?: string|null,
     *   EbsCardIndex?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deleteOnTermination = $input['DeleteOnTermination'] ?? null;
        $this->iops = $input['Iops'] ?? null;
        $this->snapshotId = $input['SnapshotId'] ?? null;
        $this->volumeSize = $input['VolumeSize'] ?? null;
        $this->volumeType = $input['VolumeType'] ?? null;
        $this->kmsKeyId = $input['KmsKeyId'] ?? null;
        $this->throughput = $input['Throughput'] ?? null;
        $this->outpostArn = $input['OutpostArn'] ?? null;
        $this->availabilityZone = $input['AvailabilityZone'] ?? null;
        $this->encrypted = $input['Encrypted'] ?? null;
        $this->volumeInitializationRate = $input['VolumeInitializationRate'] ?? null;
        $this->availabilityZoneId = $input['AvailabilityZoneId'] ?? null;
        $this->ebsCardIndex = $input['EbsCardIndex'] ?? null;
    }

    /**
     * @param array{
     *   DeleteOnTermination?: bool|null,
     *   Iops?: int|null,
     *   SnapshotId?: string|null,
     *   VolumeSize?: int|null,
     *   VolumeType?: VolumeType::*|null,
     *   KmsKeyId?: string|null,
     *   Throughput?: int|null,
     *   OutpostArn?: string|null,
     *   AvailabilityZone?: string|null,
     *   Encrypted?: bool|null,
     *   VolumeInitializationRate?: int|null,
     *   AvailabilityZoneId?: string|null,
     *   EbsCardIndex?: int|null,
     * }|EbsBlockDevice $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAvailabilityZone(): ?string
    {
        return $this->availabilityZone;
    }

    public function getAvailabilityZoneId(): ?string
    {
        return $this->availabilityZoneId;
    }

    public function getDeleteOnTermination(): ?bool
    {
        return $this->deleteOnTermination;
    }

    public function getEbsCardIndex(): ?int
    {
        return $this->ebsCardIndex;
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

    public function getOutpostArn(): ?string
    {
        return $this->outpostArn;
    }

    public function getSnapshotId(): ?string
    {
        return $this->snapshotId;
    }

    public function getThroughput(): ?int
    {
        return $this->throughput;
    }

    public function getVolumeInitializationRate(): ?int
    {
        return $this->volumeInitializationRate;
    }

    public function getVolumeSize(): ?int
    {
        return $this->volumeSize;
    }

    /**
     * @return VolumeType::*|null
     */
    public function getVolumeType(): ?string
    {
        return $this->volumeType;
    }
}
