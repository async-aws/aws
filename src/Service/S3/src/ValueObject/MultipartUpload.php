<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\S3\Enum\ChecksumAlgorithm;
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
     * > **Directory buckets** - Only the S3 Express One Zone storage class is supported by directory buckets to store
     * > objects.
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
     * @param array{
     *   UploadId?: null|string,
     *   Key?: null|string,
     *   Initiated?: null|\DateTimeImmutable,
     *   StorageClass?: null|StorageClass::*,
     *   Owner?: null|Owner|array,
     *   Initiator?: null|Initiator|array,
     *   ChecksumAlgorithm?: null|ChecksumAlgorithm::*,
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
