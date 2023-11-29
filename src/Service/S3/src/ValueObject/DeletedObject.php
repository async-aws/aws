<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Information about the deleted object.
 */
final class DeletedObject
{
    /**
     * The name of the deleted object.
     *
     * @var string|null
     */
    private $key;

    /**
     * The version ID of the deleted object.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * Indicates whether the specified object version that was permanently deleted was (true) or was not (false) a delete
     * marker before deletion. In a simple DELETE, this header indicates whether (true) or not (false) the current version
     * of the object is a delete marker.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var bool|null
     */
    private $deleteMarker;

    /**
     * The version ID of the delete marker created as a result of the DELETE operation. If you delete a specific object
     * version, the value returned by this header is the version ID of the object version deleted.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $deleteMarkerVersionId;

    /**
     * @param array{
     *   Key?: null|string,
     *   VersionId?: null|string,
     *   DeleteMarker?: null|bool,
     *   DeleteMarkerVersionId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->versionId = $input['VersionId'] ?? null;
        $this->deleteMarker = $input['DeleteMarker'] ?? null;
        $this->deleteMarkerVersionId = $input['DeleteMarkerVersionId'] ?? null;
    }

    /**
     * @param array{
     *   Key?: null|string,
     *   VersionId?: null|string,
     *   DeleteMarker?: null|bool,
     *   DeleteMarkerVersionId?: null|string,
     * }|DeletedObject $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeleteMarker(): ?bool
    {
        return $this->deleteMarker;
    }

    public function getDeleteMarkerVersionId(): ?string
    {
        return $this->deleteMarkerVersionId;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getVersionId(): ?string
    {
        return $this->versionId;
    }
}
