<?php

namespace AsyncAws\S3\Result;

use AsyncAws\S3\Enum\ObjectStorageClass;

class AwsObject
{
    private $Key;

    private $LastModified;

    private $ETag;

    private $Size;

    private $StorageClass;

    private $Owner;

    /**
     * @param array{
     *   Key: ?string,
     *   LastModified: ?\DateTimeInterface,
     *   ETag: ?string,
     *   Size: ?string,
     *   StorageClass: null|\AsyncAws\S3\Enum\ObjectStorageClass::STANDARD|\AsyncAws\S3\Enum\ObjectStorageClass::REDUCED_REDUNDANCY|\AsyncAws\S3\Enum\ObjectStorageClass::GLACIER|\AsyncAws\S3\Enum\ObjectStorageClass::STANDARD_IA|\AsyncAws\S3\Enum\ObjectStorageClass::ONEZONE_IA|\AsyncAws\S3\Enum\ObjectStorageClass::INTELLIGENT_TIERING|\AsyncAws\S3\Enum\ObjectStorageClass::DEEP_ARCHIVE,
     *   Owner: null|\AsyncAws\S3\Result\Owner|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'];
        $this->LastModified = $input['LastModified'];
        $this->ETag = $input['ETag'];
        $this->Size = $input['Size'];
        $this->StorageClass = $input['StorageClass'];
        $this->Owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * The entity tag is an MD5 hash of the object. ETag reflects only changes to the contents of an object, not its
     * metadata.
     */
    public function getETag(): ?string
    {
        return $this->ETag;
    }

    /**
     * The name that you assign to an object. You use the object key to retrieve the object.
     */
    public function getKey(): ?string
    {
        return $this->Key;
    }

    /**
     * The date the Object was Last Modified.
     */
    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->LastModified;
    }

    /**
     * The owner of the object.
     */
    public function getOwner(): ?Owner
    {
        return $this->Owner;
    }

    /**
     * Size in bytes of the object.
     */
    public function getSize(): ?string
    {
        return $this->Size;
    }

    /**
     * The class of storage used to store the object.
     *
     * @return ObjectStorageClass::STANDARD|ObjectStorageClass::REDUCED_REDUNDANCY|ObjectStorageClass::GLACIER|ObjectStorageClass::STANDARD_IA|ObjectStorageClass::ONEZONE_IA|ObjectStorageClass::INTELLIGENT_TIERING|ObjectStorageClass::DEEP_ARCHIVE|null
     */
    public function getStorageClass(): ?string
    {
        return $this->StorageClass;
    }
}
