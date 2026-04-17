<?php

namespace AsyncAws\Ec2\ValueObject;

use AsyncAws\Ec2\Enum\ArchitectureValues;
use AsyncAws\Ec2\Enum\BootModeValues;
use AsyncAws\Ec2\Enum\DeviceType;
use AsyncAws\Ec2\Enum\HypervisorType;
use AsyncAws\Ec2\Enum\ImageState;
use AsyncAws\Ec2\Enum\ImageTypeValues;
use AsyncAws\Ec2\Enum\ImdsSupportValues;
use AsyncAws\Ec2\Enum\PlatformValues;
use AsyncAws\Ec2\Enum\TpmSupportValues;
use AsyncAws\Ec2\Enum\VirtualizationType;

/**
 * Describes an image.
 */
final class Image
{
    /**
     * The platform details associated with the billing code of the AMI. For more information, see Understand AMI billing
     * information [^1] in the *Amazon EC2 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ami-billing-info.html
     *
     * @var string|null
     */
    private $platformDetails;

    /**
     * The operation of the Amazon EC2 instance and the billing code that is associated with the AMI. `usageOperation`
     * corresponds to the lineitem/Operation [^1] column on your Amazon Web Services Cost and Usage Report and in the Amazon
     * Web Services Price List API [^2]. You can view these fields on the **Instances** or **AMIs** pages in the Amazon EC2
     * console, or in the responses that are returned by the DescribeImages [^3] command in the Amazon EC2 API, or the
     * describe-images [^4] command in the CLI.
     *
     * [^1]: https://docs.aws.amazon.com/cur/latest/userguide/Lineitem-columns.html#Lineitem-details-O-Operation
     * [^2]: https://docs.aws.amazon.com/awsaccountbilling/latest/aboutv2/price-changes.html
     * [^3]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_DescribeImages.html
     * [^4]: https://docs.aws.amazon.com/cli/latest/reference/ec2/describe-images.html
     *
     * @var string|null
     */
    private $usageOperation;

    /**
     * Any block device mapping entries.
     *
     * @var BlockDeviceMapping[]|null
     */
    private $blockDeviceMappings;

    /**
     * The description of the AMI that was provided during image creation.
     *
     * @var string|null
     */
    private $description;

    /**
     * Specifies whether enhanced networking with ENA is enabled.
     *
     * @var bool|null
     */
    private $enaSupport;

    /**
     * The hypervisor type of the image. Only `xen` is supported. `ovm` is not supported.
     *
     * @var HypervisorType::*|null
     */
    private $hypervisor;

    /**
     * The owner alias (`amazon` | `aws-backup-vault` | `aws-marketplace`).
     *
     * @var string|null
     */
    private $imageOwnerAlias;

    /**
     * The name of the AMI that was provided during image creation.
     *
     * @var string|null
     */
    private $name;

    /**
     * The device name of the root device volume (for example, `/dev/sda1`).
     *
     * @var string|null
     */
    private $rootDeviceName;

    /**
     * The type of root device used by the AMI. The AMI can use an Amazon EBS volume or an instance store volume.
     *
     * @var DeviceType::*|null
     */
    private $rootDeviceType;

    /**
     * Specifies whether enhanced networking with the Intel 82599 Virtual Function interface is enabled.
     *
     * @var string|null
     */
    private $sriovNetSupport;

    /**
     * The reason for the state change.
     *
     * @var StateReason|null
     */
    private $stateReason;

    /**
     * Any tags assigned to the image.
     *
     * @var Tag[]|null
     */
    private $tags;

    /**
     * The type of virtualization of the AMI.
     *
     * @var VirtualizationType::*|null
     */
    private $virtualizationType;

    /**
     * The boot mode of the image. For more information, see Instance launch behavior with Amazon EC2 boot modes [^1] in the
     * *Amazon EC2 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ami-boot.html
     *
     * @var BootModeValues::*|null
     */
    private $bootMode;

    /**
     * If the image is configured for NitroTPM support, the value is `v2.0`. For more information, see NitroTPM [^1] in the
     * *Amazon EC2 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/nitrotpm.html
     *
     * @var TpmSupportValues::*|null
     */
    private $tpmSupport;

    /**
     * The date and time to deprecate the AMI, in UTC, in the following format: *YYYY*-*MM*-*DD*T*HH*:*MM*:*SS*Z. If you
     * specified a value for seconds, Amazon EC2 rounds the seconds to the nearest minute.
     *
     * @var string|null
     */
    private $deprecationTime;

    /**
     * If `v2.0`, it indicates that IMDSv2 is specified in the AMI. Instances launched from this AMI will have `HttpTokens`
     * automatically set to `required` so that, by default, the instance requires that IMDSv2 is used when requesting
     * instance metadata. In addition, `HttpPutResponseHopLimit` is set to `2`. For more information, see Configure the AMI
     * [^1] in the *Amazon EC2 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/configuring-IMDS-new-instances.html#configure-IMDS-new-instances-ami-configuration
     *
     * @var ImdsSupportValues::*|null
     */
    private $imdsSupport;

    /**
     * The ID of the instance that the AMI was created from if the AMI was created using CreateImage [^1]. This field only
     * appears if the AMI was created using CreateImage.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/APIReference/API_CreateImage.html
     *
     * @var string|null
     */
    private $sourceInstanceId;

    /**
     * Indicates whether deregistration protection is enabled for the AMI.
     *
     * @var string|null
     */
    private $deregistrationProtection;

    /**
     * The date and time, in ISO 8601 date-time format [^1], when the AMI was last used to launch an EC2 instance. When the
     * AMI is used to launch an instance, there is a 24-hour delay before that usage is reported.
     *
     * > `lastLaunchedTime` data is available starting April 2017.
     *
     * [^1]: http://www.iso.org/iso/iso8601
     *
     * @var string|null
     */
    private $lastLaunchedTime;

    /**
     * If `true`, the AMI satisfies the criteria for Allowed AMIs and can be discovered and used in the account. If `false`
     * and Allowed AMIs is set to `enabled`, the AMI can't be discovered or used in the account. If `false` and Allowed AMIs
     * is set to `audit-mode`, the AMI can be discovered and used in the account.
     *
     * For more information, see Control the discovery and use of AMIs in Amazon EC2 with Allowed AMIs [^1] in *Amazon EC2
     * User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSEC2/latest/UserGuide/ec2-allowed-amis.html
     *
     * @var bool|null
     */
    private $imageAllowed;

    /**
     * The ID of the source AMI from which the AMI was created.
     *
     * @var string|null
     */
    private $sourceImageId;

    /**
     * The Region of the source AMI.
     *
     * @var string|null
     */
    private $sourceImageRegion;

    /**
     * Indicates whether the image is eligible for Amazon Web Services Free Tier.
     *
     * - If `true`, the AMI is eligible for Free Tier and can be used to launch instances under the Free Tier limits.
     * - If `false`, the AMI is not eligible for Free Tier.
     *
     * @var bool|null
     */
    private $freeTierEligible;

    /**
     * The ID of the AMI.
     *
     * @var string|null
     */
    private $imageId;

    /**
     * The location of the AMI.
     *
     * @var string|null
     */
    private $imageLocation;

    /**
     * The current state of the AMI. If the state is `available`, the image is successfully registered and can be used to
     * launch an instance.
     *
     * @var ImageState::*|null
     */
    private $state;

    /**
     * The ID of the Amazon Web Services account that owns the image.
     *
     * @var string|null
     */
    private $ownerId;

    /**
     * The date and time the image was created.
     *
     * @var string|null
     */
    private $creationDate;

    /**
     * Indicates whether the image has public launch permissions. The value is `true` if this image has public launch
     * permissions or `false` if it has only implicit and explicit launch permissions.
     *
     * @var bool|null
     */
    private $public;

    /**
     * Any product codes associated with the AMI.
     *
     * @var ProductCode[]|null
     */
    private $productCodes;

    /**
     * The architecture of the image.
     *
     * @var ArchitectureValues::*|null
     */
    private $architecture;

    /**
     * The type of image.
     *
     * @var ImageTypeValues::*|null
     */
    private $imageType;

    /**
     * The kernel associated with the image, if any. Only applicable for machine images.
     *
     * @var string|null
     */
    private $kernelId;

    /**
     * The RAM disk associated with the image, if any. Only applicable for machine images.
     *
     * @var string|null
     */
    private $ramdiskId;

    /**
     * This value is set to `windows` for Windows AMIs; otherwise, it is blank.
     *
     * @var PlatformValues::*|null
     */
    private $platform;

    /**
     * @param array{
     *   PlatformDetails?: string|null,
     *   UsageOperation?: string|null,
     *   BlockDeviceMappings?: array<BlockDeviceMapping|array>|null,
     *   Description?: string|null,
     *   EnaSupport?: bool|null,
     *   Hypervisor?: HypervisorType::*|null,
     *   ImageOwnerAlias?: string|null,
     *   Name?: string|null,
     *   RootDeviceName?: string|null,
     *   RootDeviceType?: DeviceType::*|null,
     *   SriovNetSupport?: string|null,
     *   StateReason?: StateReason|array|null,
     *   Tags?: array<Tag|array>|null,
     *   VirtualizationType?: VirtualizationType::*|null,
     *   BootMode?: BootModeValues::*|null,
     *   TpmSupport?: TpmSupportValues::*|null,
     *   DeprecationTime?: string|null,
     *   ImdsSupport?: ImdsSupportValues::*|null,
     *   SourceInstanceId?: string|null,
     *   DeregistrationProtection?: string|null,
     *   LastLaunchedTime?: string|null,
     *   ImageAllowed?: bool|null,
     *   SourceImageId?: string|null,
     *   SourceImageRegion?: string|null,
     *   FreeTierEligible?: bool|null,
     *   ImageId?: string|null,
     *   ImageLocation?: string|null,
     *   State?: ImageState::*|null,
     *   OwnerId?: string|null,
     *   CreationDate?: string|null,
     *   Public?: bool|null,
     *   ProductCodes?: array<ProductCode|array>|null,
     *   Architecture?: ArchitectureValues::*|null,
     *   ImageType?: ImageTypeValues::*|null,
     *   KernelId?: string|null,
     *   RamdiskId?: string|null,
     *   Platform?: PlatformValues::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->platformDetails = $input['PlatformDetails'] ?? null;
        $this->usageOperation = $input['UsageOperation'] ?? null;
        $this->blockDeviceMappings = isset($input['BlockDeviceMappings']) ? array_map([BlockDeviceMapping::class, 'create'], $input['BlockDeviceMappings']) : null;
        $this->description = $input['Description'] ?? null;
        $this->enaSupport = $input['EnaSupport'] ?? null;
        $this->hypervisor = $input['Hypervisor'] ?? null;
        $this->imageOwnerAlias = $input['ImageOwnerAlias'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->rootDeviceName = $input['RootDeviceName'] ?? null;
        $this->rootDeviceType = $input['RootDeviceType'] ?? null;
        $this->sriovNetSupport = $input['SriovNetSupport'] ?? null;
        $this->stateReason = isset($input['StateReason']) ? StateReason::create($input['StateReason']) : null;
        $this->tags = isset($input['Tags']) ? array_map([Tag::class, 'create'], $input['Tags']) : null;
        $this->virtualizationType = $input['VirtualizationType'] ?? null;
        $this->bootMode = $input['BootMode'] ?? null;
        $this->tpmSupport = $input['TpmSupport'] ?? null;
        $this->deprecationTime = $input['DeprecationTime'] ?? null;
        $this->imdsSupport = $input['ImdsSupport'] ?? null;
        $this->sourceInstanceId = $input['SourceInstanceId'] ?? null;
        $this->deregistrationProtection = $input['DeregistrationProtection'] ?? null;
        $this->lastLaunchedTime = $input['LastLaunchedTime'] ?? null;
        $this->imageAllowed = $input['ImageAllowed'] ?? null;
        $this->sourceImageId = $input['SourceImageId'] ?? null;
        $this->sourceImageRegion = $input['SourceImageRegion'] ?? null;
        $this->freeTierEligible = $input['FreeTierEligible'] ?? null;
        $this->imageId = $input['ImageId'] ?? null;
        $this->imageLocation = $input['ImageLocation'] ?? null;
        $this->state = $input['State'] ?? null;
        $this->ownerId = $input['OwnerId'] ?? null;
        $this->creationDate = $input['CreationDate'] ?? null;
        $this->public = $input['Public'] ?? null;
        $this->productCodes = isset($input['ProductCodes']) ? array_map([ProductCode::class, 'create'], $input['ProductCodes']) : null;
        $this->architecture = $input['Architecture'] ?? null;
        $this->imageType = $input['ImageType'] ?? null;
        $this->kernelId = $input['KernelId'] ?? null;
        $this->ramdiskId = $input['RamdiskId'] ?? null;
        $this->platform = $input['Platform'] ?? null;
    }

    /**
     * @param array{
     *   PlatformDetails?: string|null,
     *   UsageOperation?: string|null,
     *   BlockDeviceMappings?: array<BlockDeviceMapping|array>|null,
     *   Description?: string|null,
     *   EnaSupport?: bool|null,
     *   Hypervisor?: HypervisorType::*|null,
     *   ImageOwnerAlias?: string|null,
     *   Name?: string|null,
     *   RootDeviceName?: string|null,
     *   RootDeviceType?: DeviceType::*|null,
     *   SriovNetSupport?: string|null,
     *   StateReason?: StateReason|array|null,
     *   Tags?: array<Tag|array>|null,
     *   VirtualizationType?: VirtualizationType::*|null,
     *   BootMode?: BootModeValues::*|null,
     *   TpmSupport?: TpmSupportValues::*|null,
     *   DeprecationTime?: string|null,
     *   ImdsSupport?: ImdsSupportValues::*|null,
     *   SourceInstanceId?: string|null,
     *   DeregistrationProtection?: string|null,
     *   LastLaunchedTime?: string|null,
     *   ImageAllowed?: bool|null,
     *   SourceImageId?: string|null,
     *   SourceImageRegion?: string|null,
     *   FreeTierEligible?: bool|null,
     *   ImageId?: string|null,
     *   ImageLocation?: string|null,
     *   State?: ImageState::*|null,
     *   OwnerId?: string|null,
     *   CreationDate?: string|null,
     *   Public?: bool|null,
     *   ProductCodes?: array<ProductCode|array>|null,
     *   Architecture?: ArchitectureValues::*|null,
     *   ImageType?: ImageTypeValues::*|null,
     *   KernelId?: string|null,
     *   RamdiskId?: string|null,
     *   Platform?: PlatformValues::*|null,
     * }|Image $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ArchitectureValues::*|null
     */
    public function getArchitecture(): ?string
    {
        return $this->architecture;
    }

    /**
     * @return BlockDeviceMapping[]
     */
    public function getBlockDeviceMappings(): array
    {
        return $this->blockDeviceMappings ?? [];
    }

    /**
     * @return BootModeValues::*|null
     */
    public function getBootMode(): ?string
    {
        return $this->bootMode;
    }

    public function getCreationDate(): ?string
    {
        return $this->creationDate;
    }

    public function getDeprecationTime(): ?string
    {
        return $this->deprecationTime;
    }

    public function getDeregistrationProtection(): ?string
    {
        return $this->deregistrationProtection;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEnaSupport(): ?bool
    {
        return $this->enaSupport;
    }

    public function getFreeTierEligible(): ?bool
    {
        return $this->freeTierEligible;
    }

    /**
     * @return HypervisorType::*|null
     */
    public function getHypervisor(): ?string
    {
        return $this->hypervisor;
    }

    public function getImageAllowed(): ?bool
    {
        return $this->imageAllowed;
    }

    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    public function getImageLocation(): ?string
    {
        return $this->imageLocation;
    }

    public function getImageOwnerAlias(): ?string
    {
        return $this->imageOwnerAlias;
    }

    /**
     * @return ImageTypeValues::*|null
     */
    public function getImageType(): ?string
    {
        return $this->imageType;
    }

    /**
     * @return ImdsSupportValues::*|null
     */
    public function getImdsSupport(): ?string
    {
        return $this->imdsSupport;
    }

    public function getKernelId(): ?string
    {
        return $this->kernelId;
    }

    public function getLastLaunchedTime(): ?string
    {
        return $this->lastLaunchedTime;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    /**
     * @return PlatformValues::*|null
     */
    public function getPlatform(): ?string
    {
        return $this->platform;
    }

    public function getPlatformDetails(): ?string
    {
        return $this->platformDetails;
    }

    /**
     * @return ProductCode[]
     */
    public function getProductCodes(): array
    {
        return $this->productCodes ?? [];
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function getRamdiskId(): ?string
    {
        return $this->ramdiskId;
    }

    public function getRootDeviceName(): ?string
    {
        return $this->rootDeviceName;
    }

    /**
     * @return DeviceType::*|null
     */
    public function getRootDeviceType(): ?string
    {
        return $this->rootDeviceType;
    }

    public function getSourceImageId(): ?string
    {
        return $this->sourceImageId;
    }

    public function getSourceImageRegion(): ?string
    {
        return $this->sourceImageRegion;
    }

    public function getSourceInstanceId(): ?string
    {
        return $this->sourceInstanceId;
    }

    public function getSriovNetSupport(): ?string
    {
        return $this->sriovNetSupport;
    }

    /**
     * @return ImageState::*|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function getStateReason(): ?StateReason
    {
        return $this->stateReason;
    }

    /**
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags ?? [];
    }

    /**
     * @return TpmSupportValues::*|null
     */
    public function getTpmSupport(): ?string
    {
        return $this->tpmSupport;
    }

    public function getUsageOperation(): ?string
    {
        return $this->usageOperation;
    }

    /**
     * @return VirtualizationType::*|null
     */
    public function getVirtualizationType(): ?string
    {
        return $this->virtualizationType;
    }
}
