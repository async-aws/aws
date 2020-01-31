<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait ListObjectsOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->IsTruncated = $data->IsTruncated;
        $this->Marker = $data->Marker;
        $this->NextMarker = $data->NextMarker;
        $this->Contents = $data->Contents;
        $this->Name = $data->Name;
        $this->Prefix = $data->Prefix;
        $this->Delimiter = $data->Delimiter;
        $this->MaxKeys = $data->MaxKeys;
        $this->CommonPrefixes = $data->CommonPrefixes;
        $this->EncodingType = $data->EncodingType;
    }
}
