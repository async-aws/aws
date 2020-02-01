<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait GetObjectAclOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->Owner = $this->xmlValueOrNull($data->Owner);
        $this->Grants = $this->xmlValueOrNull($data->Grants);
        $this->RequestCharged = $this->xmlValueOrNull($data->RequestCharged);
    }
}
