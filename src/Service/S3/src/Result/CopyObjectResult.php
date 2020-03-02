<?php

namespace AsyncAws\S3\Result;

class CopyObjectResult
{
    private $ETag;

    private $LastModified;

    /**
     * @param array{
     *   ETag: null|string,
     *   LastModified: null|\DateTimeInterface,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ETag = $input['ETag'];
        $this->LastModified = $input['LastModified'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * Returns the ETag of the new object. The ETag reflects only changes to the contents of an object, not its metadata.
     * The source and destination ETag is identical for a successfully copied object.
     */
    public function getETag(): ?string
    {
        return $this->ETag;
    }

    /**
     * Returns the date that the object was last modified.
     */
    public function getLastModified(): ?\DateTimeInterface
    {
        return $this->LastModified;
    }
}
