<?php

namespace AsyncAws\Ec2\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Ec2\Ec2Client;
use AsyncAws\Ec2\Enum\ArchitectureValues;
use AsyncAws\Ec2\Enum\BootModeValues;
use AsyncAws\Ec2\Enum\DeviceType;
use AsyncAws\Ec2\Enum\HypervisorType;
use AsyncAws\Ec2\Enum\ImageState;
use AsyncAws\Ec2\Enum\ImageTypeValues;
use AsyncAws\Ec2\Enum\ImdsSupportValues;
use AsyncAws\Ec2\Enum\PlatformValues;
use AsyncAws\Ec2\Enum\ProductCodeValues;
use AsyncAws\Ec2\Enum\TpmSupportValues;
use AsyncAws\Ec2\Enum\VirtualizationType;
use AsyncAws\Ec2\Enum\VolumeType;
use AsyncAws\Ec2\Input\DescribeImagesRequest;
use AsyncAws\Ec2\ValueObject\BlockDeviceMapping;
use AsyncAws\Ec2\ValueObject\EbsBlockDevice;
use AsyncAws\Ec2\ValueObject\Image;
use AsyncAws\Ec2\ValueObject\ProductCode;
use AsyncAws\Ec2\ValueObject\StateReason;
use AsyncAws\Ec2\ValueObject\Tag;

/**
 * @implements \IteratorAggregate<Image>
 */
class DescribeImagesResult extends Result implements \IteratorAggregate
{
    /**
     * The token to include in another request to get the next page of items. This value is `null` when there are no more
     * items to return.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Information about the images.
     *
     * @var Image[]
     */
    private $images;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Image>
     */
    public function getImages(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->images;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof Ec2Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof DescribeImagesRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextToken) {
                $input->setNextToken($page->nextToken);

                $this->registerPrefetch($nextPage = $client->describeImages($input));
            } else {
                $nextPage = null;
            }

            yield from $page->images;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * Iterates over Images.
     *
     * @return \Traversable<Image>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getImages();
    }

    public function getNextToken(): ?string
    {
        $this->initialize();

        return $this->nextToken;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->nextToken = (null !== $v = $data->nextToken[0]) ? (string) $v : null;
        $this->images = (0 === ($v = $data->imagesSet)->count()) ? [] : $this->populateResultImageList($v);
    }

    private function populateResultBlockDeviceMapping(\SimpleXMLElement $xml): BlockDeviceMapping
    {
        return new BlockDeviceMapping([
            'Ebs' => 0 === $xml->ebs->count() ? null : $this->populateResultEbsBlockDevice($xml->ebs),
            'NoDevice' => (null !== $v = $xml->noDevice[0]) ? (string) $v : null,
            'DeviceName' => (null !== $v = $xml->deviceName[0]) ? (string) $v : null,
            'VirtualName' => (null !== $v = $xml->virtualName[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return BlockDeviceMapping[]
     */
    private function populateResultBlockDeviceMappingList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->item as $item) {
            $items[] = $this->populateResultBlockDeviceMapping($item);
        }

        return $items;
    }

    private function populateResultEbsBlockDevice(\SimpleXMLElement $xml): EbsBlockDevice
    {
        return new EbsBlockDevice([
            'DeleteOnTermination' => (null !== $v = $xml->deleteOnTermination[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'Iops' => (null !== $v = $xml->iops[0]) ? (int) (string) $v : null,
            'SnapshotId' => (null !== $v = $xml->snapshotId[0]) ? (string) $v : null,
            'VolumeSize' => (null !== $v = $xml->volumeSize[0]) ? (int) (string) $v : null,
            'VolumeType' => (null !== $v = $xml->volumeType[0]) ? (!VolumeType::exists((string) $xml->volumeType) ? VolumeType::UNKNOWN_TO_SDK : (string) $xml->volumeType) : null,
            'KmsKeyId' => (null !== $v = $xml->kmsKeyId[0]) ? (string) $v : null,
            'Throughput' => (null !== $v = $xml->throughput[0]) ? (int) (string) $v : null,
            'OutpostArn' => (null !== $v = $xml->outpostArn[0]) ? (string) $v : null,
            'AvailabilityZone' => (null !== $v = $xml->availabilityZone[0]) ? (string) $v : null,
            'Encrypted' => (null !== $v = $xml->encrypted[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'VolumeInitializationRate' => (null !== $v = $xml->VolumeInitializationRate[0]) ? (int) (string) $v : null,
            'AvailabilityZoneId' => (null !== $v = $xml->AvailabilityZoneId[0]) ? (string) $v : null,
            'EbsCardIndex' => (null !== $v = $xml->EbsCardIndex[0]) ? (int) (string) $v : null,
        ]);
    }

    private function populateResultImage(\SimpleXMLElement $xml): Image
    {
        return new Image([
            'PlatformDetails' => (null !== $v = $xml->platformDetails[0]) ? (string) $v : null,
            'UsageOperation' => (null !== $v = $xml->usageOperation[0]) ? (string) $v : null,
            'BlockDeviceMappings' => (0 === ($v = $xml->blockDeviceMapping)->count()) ? null : $this->populateResultBlockDeviceMappingList($v),
            'Description' => (null !== $v = $xml->description[0]) ? (string) $v : null,
            'EnaSupport' => (null !== $v = $xml->enaSupport[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'Hypervisor' => (null !== $v = $xml->hypervisor[0]) ? (!HypervisorType::exists((string) $xml->hypervisor) ? HypervisorType::UNKNOWN_TO_SDK : (string) $xml->hypervisor) : null,
            'ImageOwnerAlias' => (null !== $v = $xml->imageOwnerAlias[0]) ? (string) $v : null,
            'Name' => (null !== $v = $xml->name[0]) ? (string) $v : null,
            'RootDeviceName' => (null !== $v = $xml->rootDeviceName[0]) ? (string) $v : null,
            'RootDeviceType' => (null !== $v = $xml->rootDeviceType[0]) ? (!DeviceType::exists((string) $xml->rootDeviceType) ? DeviceType::UNKNOWN_TO_SDK : (string) $xml->rootDeviceType) : null,
            'SriovNetSupport' => (null !== $v = $xml->sriovNetSupport[0]) ? (string) $v : null,
            'StateReason' => 0 === $xml->stateReason->count() ? null : $this->populateResultStateReason($xml->stateReason),
            'Tags' => (0 === ($v = $xml->tagSet)->count()) ? null : $this->populateResultTagList($v),
            'VirtualizationType' => (null !== $v = $xml->virtualizationType[0]) ? (!VirtualizationType::exists((string) $xml->virtualizationType) ? VirtualizationType::UNKNOWN_TO_SDK : (string) $xml->virtualizationType) : null,
            'BootMode' => (null !== $v = $xml->bootMode[0]) ? (!BootModeValues::exists((string) $xml->bootMode) ? BootModeValues::UNKNOWN_TO_SDK : (string) $xml->bootMode) : null,
            'TpmSupport' => (null !== $v = $xml->tpmSupport[0]) ? (!TpmSupportValues::exists((string) $xml->tpmSupport) ? TpmSupportValues::UNKNOWN_TO_SDK : (string) $xml->tpmSupport) : null,
            'DeprecationTime' => (null !== $v = $xml->deprecationTime[0]) ? (string) $v : null,
            'ImdsSupport' => (null !== $v = $xml->imdsSupport[0]) ? (!ImdsSupportValues::exists((string) $xml->imdsSupport) ? ImdsSupportValues::UNKNOWN_TO_SDK : (string) $xml->imdsSupport) : null,
            'SourceInstanceId' => (null !== $v = $xml->sourceInstanceId[0]) ? (string) $v : null,
            'DeregistrationProtection' => (null !== $v = $xml->deregistrationProtection[0]) ? (string) $v : null,
            'LastLaunchedTime' => (null !== $v = $xml->lastLaunchedTime[0]) ? (string) $v : null,
            'ImageAllowed' => (null !== $v = $xml->imageAllowed[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'SourceImageId' => (null !== $v = $xml->sourceImageId[0]) ? (string) $v : null,
            'SourceImageRegion' => (null !== $v = $xml->sourceImageRegion[0]) ? (string) $v : null,
            'FreeTierEligible' => (null !== $v = $xml->freeTierEligible[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'ImageId' => (null !== $v = $xml->imageId[0]) ? (string) $v : null,
            'ImageLocation' => (null !== $v = $xml->imageLocation[0]) ? (string) $v : null,
            'State' => (null !== $v = $xml->imageState[0]) ? (!ImageState::exists((string) $xml->imageState) ? ImageState::UNKNOWN_TO_SDK : (string) $xml->imageState) : null,
            'OwnerId' => (null !== $v = $xml->imageOwnerId[0]) ? (string) $v : null,
            'CreationDate' => (null !== $v = $xml->creationDate[0]) ? (string) $v : null,
            'Public' => (null !== $v = $xml->isPublic[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'ProductCodes' => (0 === ($v = $xml->productCodes)->count()) ? null : $this->populateResultProductCodeList($v),
            'Architecture' => (null !== $v = $xml->architecture[0]) ? (!ArchitectureValues::exists((string) $xml->architecture) ? ArchitectureValues::UNKNOWN_TO_SDK : (string) $xml->architecture) : null,
            'ImageType' => (null !== $v = $xml->imageType[0]) ? (!ImageTypeValues::exists((string) $xml->imageType) ? ImageTypeValues::UNKNOWN_TO_SDK : (string) $xml->imageType) : null,
            'KernelId' => (null !== $v = $xml->kernelId[0]) ? (string) $v : null,
            'RamdiskId' => (null !== $v = $xml->ramdiskId[0]) ? (string) $v : null,
            'Platform' => (null !== $v = $xml->platform[0]) ? (!PlatformValues::exists((string) $xml->platform) ? PlatformValues::UNKNOWN_TO_SDK : (string) $xml->platform) : null,
        ]);
    }

    /**
     * @return Image[]
     */
    private function populateResultImageList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->item as $item) {
            $items[] = $this->populateResultImage($item);
        }

        return $items;
    }

    private function populateResultProductCode(\SimpleXMLElement $xml): ProductCode
    {
        return new ProductCode([
            'ProductCodeId' => (null !== $v = $xml->productCode[0]) ? (string) $v : null,
            'ProductCodeType' => (null !== $v = $xml->type[0]) ? (!ProductCodeValues::exists((string) $xml->type) ? ProductCodeValues::UNKNOWN_TO_SDK : (string) $xml->type) : null,
        ]);
    }

    /**
     * @return ProductCode[]
     */
    private function populateResultProductCodeList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->item as $item) {
            $items[] = $this->populateResultProductCode($item);
        }

        return $items;
    }

    private function populateResultStateReason(\SimpleXMLElement $xml): StateReason
    {
        return new StateReason([
            'Code' => (null !== $v = $xml->code[0]) ? (string) $v : null,
            'Message' => (null !== $v = $xml->message[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultTag(\SimpleXMLElement $xml): Tag
    {
        return new Tag([
            'Key' => (null !== $v = $xml->key[0]) ? (string) $v : null,
            'Value' => (null !== $v = $xml->value[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Tag[]
     */
    private function populateResultTagList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml->item as $item) {
            $items[] = $this->populateResultTag($item);
        }

        return $items;
    }
}
