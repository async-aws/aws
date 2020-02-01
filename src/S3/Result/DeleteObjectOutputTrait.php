<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait DeleteObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->DeleteMarker = $this->xmlValueOrNull($data->DeleteMarker);
        $this->VersionId = $this->xmlValueOrNull($data->VersionId);
        $this->RequestCharged = $this->xmlValueOrNull($data->RequestCharged);
    }
}
