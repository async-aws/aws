<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class CreateBucketOutput extends Result
{
    use CreateBucketOutputTrait;

    private $Location;

    public function getLocation(): ?string
    {
        $this->initialize();

        return $this->Location;
    }
}
