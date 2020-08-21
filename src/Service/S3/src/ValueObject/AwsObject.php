<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\S3\Enum\ObjectStorageClass;

final class AwsObject
{
    /**
     * The name that you assign to an object. You use the object key to retrieve the object.
     */
    private $Key;

    /**
     * The date the Object was Last Modified.
     */
    private $LastModified;

    /**
     * The entity tag is a hash of the object. The ETag reflects changes only to the contents of an object, not its
     * metadata. The ETag may or may not be an MD5 digest of the object data. Whether or not it is depends on how the object
     * was created and how it is encrypted as described below:.
     */
    private $ETag;

    /**
     * Size in bytes of the object.
     */
    private $Size;

    /**
     * The class of storage used to store the object.
     */
    private $StorageClass;

    /**
     * The owner of the object.
     */
    private $Owner;

    /**
     * @param array{
     *   Key?: null|string,
     *   LastModified?: null|\DateTimeImmutable,
     *   ETag?: null|string,
     *   Size?: null|string,
     *   StorageClass?: null|ObjectStorageClass::*,
     *   Owner?: null|Owner|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'] ?? null;
        $this->LastModified = $input['LastModified'] ?? null;
        $this->ETag = $input['ETag'] ?? null;
        $this->Size = $input['Size'] ?? null;
        $this->StorageClass = $input['StorageClass'] ?? null;
        $this->Owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getETag(): ?string
    {
        return $this->ETag;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        return $this->LastModified;
    }

    public function getOwner(): ?Owner
    {
        return $this->Owner;
    }

    public function getSize(): ?string
    {
        return $this->Size;
    }

    /**
     * @return ObjectStorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->StorageClass;
    }
}
