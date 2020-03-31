<?php

namespace AsyncAws\S3\ValueObject;

class Part
{
    /**
     * Part number identifying the part. This is a positive integer between 1 and 10,000.
     */
    private $PartNumber;

    /**
     * Date and time at which the part was uploaded.
     */
    private $LastModified;

    /**
     * Entity tag returned when the part was uploaded.
     */
    private $ETag;

    /**
     * Size in bytes of the uploaded part data.
     */
    private $Size;

    /**
     * @param array{
     *   PartNumber?: null|int,
     *   LastModified?: null|\DateTimeImmutable,
     *   ETag?: null|string,
     *   Size?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->PartNumber = $input['PartNumber'] ?? null;
        $this->LastModified = $input['LastModified'] ?? null;
        $this->ETag = $input['ETag'] ?? null;
        $this->Size = $input['Size'] ?? null;
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

    public function getPartNumber(): ?int
    {
        return $this->PartNumber;
    }

    public function getSize(): ?string
    {
        return $this->Size;
    }
}
