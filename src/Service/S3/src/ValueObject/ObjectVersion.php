<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\ObjectVersionStorageClass;

/**
 * The version of an object.
 */
final class ObjectVersion
{
    /**
     * The entity tag is an MD5 hash of that version of the object.
     *
     * @var string|null
     */
    private $etag;

    /**
     * The algorithm that was used to create a checksum of the object.
     *
     * @var list<ChecksumAlgorithm::*>|null
     */
    private $checksumAlgorithm;

    /**
     * Size in bytes of the object.
     *
     * @var int|null
     */
    private $size;

    /**
     * The class of storage used to store the object.
     *
     * @var ObjectVersionStorageClass::*|null
     */
    private $storageClass;

    /**
     * The object key.
     *
     * @var string|null
     */
    private $key;

    /**
     * Version ID of an object.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * Specifies whether the object is (true) or is not (false) the latest version of an object.
     *
     * @var bool|null
     */
    private $isLatest;

    /**
     * Date and time when the object was last modified.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModified;

    /**
     * Specifies the owner of the object.
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * Specifies the restoration status of an object. Objects in certain storage classes must be restored before they can be
     * retrieved. For more information about these storage classes and how to work with archived objects, see Working with
     * archived objects [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/archived-objects.html
     *
     * @var RestoreStatus|null
     */
    private $restoreStatus;

    /**
     * @param array{
     *   ETag?: null|string,
     *   ChecksumAlgorithm?: null|array<ChecksumAlgorithm::*>,
     *   Size?: null|int,
     *   StorageClass?: null|ObjectVersionStorageClass::*,
     *   Key?: null|string,
     *   VersionId?: null|string,
     *   IsLatest?: null|bool,
     *   LastModified?: null|\DateTimeImmutable,
     *   Owner?: null|Owner|array,
     *   RestoreStatus?: null|RestoreStatus|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->etag = $input['ETag'] ?? null;
        $this->checksumAlgorithm = $input['ChecksumAlgorithm'] ?? null;
        $this->size = $input['Size'] ?? null;
        $this->storageClass = $input['StorageClass'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->versionId = $input['VersionId'] ?? null;
        $this->isLatest = $input['IsLatest'] ?? null;
        $this->lastModified = $input['LastModified'] ?? null;
        $this->owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
        $this->restoreStatus = isset($input['RestoreStatus']) ? RestoreStatus::create($input['RestoreStatus']) : null;
    }

    /**
     * @param array{
     *   ETag?: null|string,
     *   ChecksumAlgorithm?: null|array<ChecksumAlgorithm::*>,
     *   Size?: null|int,
     *   StorageClass?: null|ObjectVersionStorageClass::*,
     *   Key?: null|string,
     *   VersionId?: null|string,
     *   IsLatest?: null|bool,
     *   LastModified?: null|\DateTimeImmutable,
     *   Owner?: null|Owner|array,
     *   RestoreStatus?: null|RestoreStatus|array,
     * }|ObjectVersion $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<ChecksumAlgorithm::*>
     */
    public function getChecksumAlgorithm(): array
    {
        return $this->checksumAlgorithm ?? [];
    }

    public function getEtag(): ?string
    {
        return $this->etag;
    }

    public function getIsLatest(): ?bool
    {
        return $this->isLatest;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        return $this->lastModified;
    }

    public function getOwner(): ?Owner
    {
        return $this->owner;
    }

    public function getRestoreStatus(): ?RestoreStatus
    {
        return $this->restoreStatus;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    /**
     * @return ObjectVersionStorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
    }

    public function getVersionId(): ?string
    {
        return $this->versionId;
    }
}
