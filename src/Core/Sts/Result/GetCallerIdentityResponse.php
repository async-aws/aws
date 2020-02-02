<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Result;

class GetCallerIdentityResponse extends Result
{
    use GetCallerIdentityResponseTrait;

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
