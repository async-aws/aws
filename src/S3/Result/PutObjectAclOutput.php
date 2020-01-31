<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Aws\Result;

class PutObjectAclOutput extends Result
{
    use PutObjectAclOutputTrait;

    private $RequestCharged;

    public function getRequestCharged(): string
    {
        $this->initialize();

        return $this->RequestCharged;
    }
}
