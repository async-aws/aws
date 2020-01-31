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
        $this->initialize();

        return $this->Owner;
    }

    public function getGrants(): ?array
    {
        $this->initialize();

        return $this->Grants;
    }

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }
}
