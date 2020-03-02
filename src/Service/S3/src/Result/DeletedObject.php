<?php

namespace AsyncAws\S3\Result;

class DeletedObject
{
    private $Key;

    private $VersionId;

    private $DeleteMarker;

    private $DeleteMarkerVersionId;

    /**
     * @param array{
     *   Key: null|string,
     *   VersionId: null|string,
     *   DeleteMarker: null|bool,
     *   DeleteMarkerVersionId: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'];
        $this->VersionId = $input['VersionId'];
        $this->DeleteMarker = $input['DeleteMarker'];
        $this->DeleteMarkerVersionId = $input['DeleteMarkerVersionId'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * Specifies whether the versioned object that was permanently deleted was (true) or was not (false) a delete marker. In
     * a simple DELETE, this header indicates whether (true) or not (false) a delete marker was created.
     */
    public function getDeleteMarker(): ?bool
    {
        return $this->DeleteMarker;
    }

    /**
     * The version ID of the delete marker created as a result of the DELETE operation. If you delete a specific object
     * version, the value returned by this header is the version ID of the object version deleted.
     */
    public function getDeleteMarkerVersionId(): ?string
    {
        return $this->DeleteMarkerVersionId;
    }

    /**
     * The name of the deleted object.
     */
    public function getKey(): ?string
    {
        return $this->Key;
    }

    /**
     * The version ID of the deleted object.
     */
    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }
}
