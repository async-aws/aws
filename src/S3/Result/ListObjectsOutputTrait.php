<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait ListObjectsOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->IsTruncated = $this->xmlValueOrNull($data->IsTruncated);
        $this->Marker = $this->xmlValueOrNull($data->Marker);
        $this->NextMarker = $this->xmlValueOrNull($data->NextMarker);
        $this->Contents = $this->xmlValueOrNull($data->Contents);
        $this->Name = $this->xmlValueOrNull($data->Name);
        $this->Prefix = $this->xmlValueOrNull($data->Prefix);
        $this->Delimiter = $this->xmlValueOrNull($data->Delimiter);
        $this->MaxKeys = $this->xmlValueOrNull($data->MaxKeys);
        $this->CommonPrefixes = $this->xmlValueOrNull($data->CommonPrefixes);
        $this->EncodingType = $this->xmlValueOrNull($data->EncodingType);
    }
}
