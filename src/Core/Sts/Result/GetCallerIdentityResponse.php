<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetCallerIdentityResponse extends Result
{
    private $UserId;

    private $Account;

    private $Arn;

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->GetCallerIdentityResult;

        $this->UserId = $this->xmlValueOrNull($data->UserId, 'string');
        $this->Account = $this->xmlValueOrNull($data->Account, 'string');
        $this->Arn = $this->xmlValueOrNull($data->Arn, 'string');
    }

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
