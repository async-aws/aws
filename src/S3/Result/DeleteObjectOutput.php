<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class DeleteObjectOutput extends Result
{
    use DeleteObjectOutputTrait;

    private $DeleteMarker;

    private $VersionId;

    private $RequestCharged;

    public function getDeleteMarker(): ?bool
    {
        $this->resolve();

        return $this->DeleteMarker;
    }

    public function getVersionId(): ?string
    {
        $this->resolve();

        return $this->VersionId;
    }

    public function getRequestCharged(): ?string
    {
        $this->resolve();

        return $this->RequestCharged;
    }
}
