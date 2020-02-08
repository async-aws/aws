<?php

namespace AsyncAws\S3\Result;

class DeletedObject
{
    /**
     * The name of the deleted object.
     */
    private $Key;

    /**
     * The version ID of the deleted object.
     */
    private $VersionId;

    /**
     * Specifies whether the versioned object that was permanently deleted was (true) or was not (false) a delete marker. In
     * a simple DELETE, this header indicates whether (true) or not (false) a delete marker was created.
     */
    private $DeleteMarker;

    /**
     * The version ID of the delete marker created as a result of the DELETE operation. If you delete a specific object
     * version, the value returned by this header is the version ID of the object version deleted.
     */
    private $DeleteMarkerVersionId;

    /**
     * @param array{
     *   Key?: string,
     *   VersionId?: string,
     *   DeleteMarker?: bool,
     *   DeleteMarkerVersionId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Key = $input['Key'] ?? null;
        $this->VersionId = $input['VersionId'] ?? null;
        $this->DeleteMarker = $input['DeleteMarker'] ?? null;
        $this->DeleteMarkerVersionId = $input['DeleteMarkerVersionId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeleteMarker(): ?bool
    {
        return $this->DeleteMarker;
    }

    public function getDeleteMarkerVersionId(): ?string
    {
        return $this->DeleteMarkerVersionId;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }
}
