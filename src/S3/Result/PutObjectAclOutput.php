<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class PutObjectAclOutput extends Result
{
    use PutObjectAclOutputTrait;

    private $RequestCharged;

    public function getRequestCharged(): ?string
    {
        $this->resolve();

        return $this->RequestCharged;
    }
}
