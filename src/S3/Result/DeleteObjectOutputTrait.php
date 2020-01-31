<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait DeleteObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->DeleteMarker = $data->DeleteMarker;
        $this->VersionId = $data->VersionId;
        $this->RequestCharged = $data->RequestCharged;
    }
}
