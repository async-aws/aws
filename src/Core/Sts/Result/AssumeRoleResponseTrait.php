<?php

namespace AsyncAws\Core\Sts\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait AssumeRoleResponseTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
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
}
