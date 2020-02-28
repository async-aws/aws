<?php

namespace AsyncAws\S3\Result;

class AwsObject
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
     * The entity tag is an MD5 hash of the object. ETag reflects only changes to the contents of an object, not its
     * metadata.
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
     *   Key: ?string,
     *   LastModified: ?\DateTimeInterface,
     *   ETag: ?string,
     *   Size: ?string,
     *   StorageClass: ?string,
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

    public function getETag(): ?string
    {
        return $this->ETag;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getLastModified(): ?\DateTimeInterface
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

    public function getStorageClass(): ?string
    {
        return $this->StorageClass;
    }
}
