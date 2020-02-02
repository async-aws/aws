<?php

namespace AsyncAws\Core\Sts\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class AssumeRoleResponse extends Result
{
    private $Credentials;

    private $AssumedRoleUser;

    private $PackedPolicySize;

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->AssumeRoleResult;

        $this->Credentials = new Credentials([
        'AccessKeyId' => $this->xmlValueOrNull($data->AccessKeyId, 'string'),
        'SecretAccessKey' => $this->xmlValueOrNull($data->SecretAccessKey, 'string'),
        'SessionToken' => $this->xmlValueOrNull($data->SessionToken, 'string'),
        'Expiration' => $this->xmlValueOrNull($data->Expiration, '\DateTimeImmutable'),
        ]);
        $this->AssumedRoleUser = new AssumedRoleUser([
        'AssumedRoleId' => $this->xmlValueOrNull($data->AssumedRoleId, 'string'),
        'Arn' => $this->xmlValueOrNull($data->Arn, 'string'),
        ]);
        $this->PackedPolicySize = $this->xmlValueOrNull($data->PackedPolicySize, 'int');
    }

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
