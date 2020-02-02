<?php

namespace AsyncAws\S3\Result;

class CopyObjectResult
{
    private $ETag;
    private $LastModified;

    /**
     * @param array{
     *   ETag?: string,
     *   LastModified?: \DateTimeImmutable|string,
     * } $input
     */
    public function __construct(array $input = [])
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
