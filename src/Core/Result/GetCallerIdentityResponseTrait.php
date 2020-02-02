<?php

namespace AsyncAws\Core\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait GetCallerIdentityResponseTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $data = $data->GetCallerIdentityResult;
        $this->UserId = $this->xmlValueOrNull($data->UserId);
        $this->Account = $this->xmlValueOrNull($data->Account);
        $this->Arn = $this->xmlValueOrNull($data->Arn);
    }
}
