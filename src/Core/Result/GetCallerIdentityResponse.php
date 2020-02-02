<?php

namespace AsyncAws\Core\Result;

use AsyncAws\Core\Result;

class GetCallerIdentityResponse extends Result
{
    use Result\GetCallerIdentityResponseTrait;

    private $UserId;

    private $Account;

    private $Arn;

    public function getUserId(): ?string
    {
        $this->initialize();

        return $this->UserId;
    }

    public function getAccount(): ?string
    {
        $this->initialize();

        return $this->Account;
    }

    public function getArn(): ?string
    {
        $this->initialize();

        return $this->Arn;
    }
}
