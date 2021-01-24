<?php

namespace AsyncAws\S3\ValueObject;

/**
 * Container for all response elements.
 */
final class CopyObjectResult
{
    /**
     * Returns the ETag of the new object. The ETag reflects only changes to the contents of an object, not its metadata.
     * The source and destination ETag is identical for a successfully copied object.
     */
    private $ETag;

    /**
     * Returns the date that the object was last modified.
     */
    private $LastModified;

    /**
     * @param array{
     *   ETag?: null|string,
     *   LastModified?: null|\DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ETag = $input['ETag'] ?? null;
        $this->LastModified = $input['LastModified'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getETag(): ?string
    {
        return $this->ETag;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        return $this->LastModified;
    }
}
