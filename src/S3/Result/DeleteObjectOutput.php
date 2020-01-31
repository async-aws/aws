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
        $this->initialize();

        return $this->DeleteMarker;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }
}
