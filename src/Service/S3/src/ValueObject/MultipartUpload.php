<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\ChecksumType;
use AsyncAws\S3\Enum\StorageClass;

/**
 * Container for the `MultipartUpload` for the Amazon S3 object.
 */
final class MultipartUpload
{
    /**
     * Upload ID that identifies the multipart upload.
     *
     * @var string|null
     */
    private $uploadId;

    /**
     * Key of the object for which the multipart upload was initiated.
     *
     * @var string|null
     */
    private $key;

    /**
     * Date and time at which the multipart upload was initiated.
     *
     * @var \DateTimeImmutable|null
     */
    private $initiated;

    /**
     * The class of storage used to store the object.
     *
     * > **Directory buckets** - Directory buckets only support `EXPRESS_ONEZONE` (the S3 Express One Zone storage class) in
     * > Availability Zones and `ONEZONE_IA` (the S3 One Zone-Infrequent Access storage class) in Dedicated Local Zones.
     *
     * @var StorageClass::*|null
     */
    private $storageClass;

    /**
     * Specifies the owner of the object that is part of the multipart upload.
     *
     * > **Directory buckets** - The bucket owner is returned as the object owner for all the objects.
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * Identifies who initiated the multipart upload.
     *
     * @var Initiator|null
     */
    private $initiator;

    /**
     * The algorithm that was used to create a checksum of the object.
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    /**
     * The checksum type that is used to calculate the object’s checksum value. For more information, see Checking object
     * integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var ChecksumType::*|null
     */
    private $checksumType;

    /**
     * @param array{
     *   UploadId?: null|string,
     *   Key?: null|string,
     *   Initiated?: null|\DateTimeImmutable,
     *   StorageClass?: null|StorageClass::*,
     *   Owner?: null|Owner|array,
     *   Initiator?: null|Initiator|array,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ChecksumType?: null|ChecksumType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->uploadId = $input['UploadId'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->initiated = $input['Initiated'] ?? null;
        $this->storageClass = $input['StorageClass'] ?? null;
        $this->owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
        $this->initiator = isset($input['Initiator']) ? Initiator::create($input['Initiator']) : null;
        $this->checksumAlgorithm = $input['ChecksumAlgorithm'] ?? null;
        $this->checksumType = $input['ChecksumType'] ?? null;
    }

    /**
     * @param array{
     *   UploadId?: null|string,
     *   Key?: null|string,
     *   Initiated?: null|\DateTimeImmutable,
     *   StorageClass?: null|StorageClass::*,
     *   Owner?: null|Owner|array,
     *   Initiator?: null|Initiator|array,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
     *   ChecksumType?: null|ChecksumType::*,
     * }|MultipartUpload $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ChecksumAlgorithm::*|null
     */
    public function getChecksumAlgorithm(): ?string
    {
        return $this->checksumAlgorithm;
    }

    /**
     * @return ChecksumType::*|null
     */
    public function getChecksumType(): ?string
    {
        return $this->checksumType;
    }

    public function getInitiated(): ?\DateTimeImmutable
    {
        return $this->initiated;
    }

    public function getInitiator(): ?Initiator
    {
        return $this->initiator;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
    }

    public function getUploadId(): ?string
    {
        return $this->uploadId;
    }
}
