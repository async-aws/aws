<?php

namespace AsyncAws\S3\Result;

class CopyObjectResult
{
    private $ETag;

    private $LastModified;

    public function getETag(): ?string
    {
        return $this->ETag;
    }

    public function getLastModified(): ?int
    {
        return $this->LastModified;
    }
}
