<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Information about the delete marker.
 */
final class DeleteMarkerEntry
{
    /**
     * The account that created the delete marker.>.
     *
     * @var Owner|null
     */
    private $owner;

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
     * @param array{
     *   Owner?: null|Owner|array,
     *   Key?: null|string,
     *   VersionId?: null|string,
     *   IsLatest?: null|bool,
     *   LastModified?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->owner = isset($input['Owner']) ? Owner::create($input['Owner']) : null;
        $this->key = $input['Key'] ?? null;
        $this->versionId = $input['VersionId'] ?? null;
        $this->isLatest = $input['IsLatest'] ?? null;
        $this->lastModified = $input['LastModified'] ?? null;
    }

    /**
     * @param array{
     *   Owner?: null|Owner|array,
     *   Key?: null|string,
     *   VersionId?: null|string,
     *   IsLatest?: null|bool,
     *   LastModified?: null|\DateTimeImmutable,
     * }|DeleteMarkerEntry $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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

    public function getVersionId(): ?string
    {
        return $this->versionId;
    }
}
