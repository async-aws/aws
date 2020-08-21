<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\S3\Enum\StorageClass;

final class MultipartUpload
{
    /**
     * Upload ID that identifies the multipart upload.
     */
    private $UploadId;

    /**
     * Key of the object for which the multipart upload was initiated.
     */
    private $Key;

    /**
     * Date and time at which the multipart upload was initiated.
     */
    private $Initiated;

    /**
     * The class of storage used to store the object.
     */
    private $StorageClass;

    /**
     * Specifies the owner of the object that is part of the multipart upload.
     */
    private $Owner;

    /**
     * Identifies who initiated the multipart upload.
     */
    private $Initiator;

    /**
     * @param array{
     *   UploadId?: null|string,
     *   Key?: null|string,
     *   Initiated?: null|\DateTimeImmutable,
     *   StorageClass?: null|StorageClass::*,
     *   Owner?: null|Owner|array,
     *   Initiator?: null|Initiator|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->UploadId = $input['UploadId'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->Initiated = $input['Initiated'] ?? null;
        $this->StorageClass = $input['StorageClass'] ?? null;
        $this->Owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
        $this->Initiator = isset($input['Initiator']) ? Initiator::create($input['Initiator']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getInitiated(): ?\DateTimeImmutable
    {
        return $this->Initiated;
    }

    public function getInitiator(): ?Initiator
    {
        return $this->Initiator;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getOwner(): ?Owner
    {
        return $this->Owner;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->StorageClass;
    }

    public function getUploadId(): ?string
    {
        return $this->UploadId;
    }
}
