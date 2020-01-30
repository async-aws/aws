<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Aws\ResultPromise;

class CreateBucketOutput extends ResultPromise
{
    private $Location;

    public function getLocation(): string
    {
        $this->initialize();
        return $this->Location;
    }
}
