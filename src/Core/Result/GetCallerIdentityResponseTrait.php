<?php

namespace AsyncAws\Core\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait GetCallerIdentityResponseTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $data = $data->GetCallerIdentityResult;

        $this->UserId = $this->xmlValueOrNull($data->UserId, 'string');
        $this->Account = $this->xmlValueOrNull($data->Account, 'string');
        $this->Arn = $this->xmlValueOrNull($data->Arn, 'string');
    }
}
