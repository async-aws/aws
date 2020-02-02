<?php

namespace AsyncAws\S3\Result;

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
     *   Key?: string,
     *   LastModified?: \DateTimeImmutable|string,
     *   ETag?: string,
     *   Size?: string,
     *   StorageClass?: string,
     *   Owner?: \AsyncAws\S3\Result\Owner|array,
     * } $input
     */
    public function __construct(array $input = [])
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

    public function getStorageClass(): ?string
    {
        return $this->StorageClass;
    }
}
