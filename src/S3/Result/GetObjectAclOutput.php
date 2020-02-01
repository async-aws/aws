<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class GetObjectAclOutput extends Result
{
    use GetObjectAclOutputTrait;

    private $Owner;

    private $Grants;

    private $RequestCharged;

    public function getOwner(): ?Owner
    {
        $this->resolve();

        return $this->Owner;
    }

    public function getGrants(): ?array
    {
        $this->resolve();

        return $this->Grants;
    }

    public function getRequestCharged(): ?string
    {
        $this->resolve();

        return $this->RequestCharged;
    }
}
