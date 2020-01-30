<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Aws\Result;

class CreateBucketOutput extends Result
{
    private $Location;

    public function getLocation(): string
    {
        $this->initialize();
        return $this->Location;
    }
}
