<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Result;

class AssumeRoleResponse extends Result
{
    use AssumeRoleResponseTrait;

    private $Credentials;

    private $AssumedRoleUser;

    private $PackedPolicySize;

    public function getCredentials(): ?Credentials
    {
        $this->initialize();

        return $this->Credentials;
    }

    public function getAssumedRoleUser(): ?AssumedRoleUser
    {
        $this->initialize();

        return $this->AssumedRoleUser;
    }

    public function getPackedPolicySize(): ?int
    {
        $this->initialize();

        return $this->PackedPolicySize;
    }
}
