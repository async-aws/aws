<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait ListObjectsOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));
        $this->IsTruncated = $this->xmlValueOrNull($data->IsTruncated, 'bool');
        $this->Marker = $this->xmlValueOrNull($data->Marker, 'string');
        $this->NextMarker = $this->xmlValueOrNull($data->NextMarker, 'string');
        $this->Contents = [];
        foreach ($data->Contents as $item) {
            $this->Contents[] = new AwsObject([
        'Key' => $this->xmlValueOrNull($item->Key, 'string'),
        'LastModified' => $this->xmlValueOrNull($item->LastModified, '\DateTimeImmutable'),
        'ETag' => $this->xmlValueOrNull($item->ETag, 'string'),
        'Size' => $this->xmlValueOrNull($item->Size, 'string'),
        'StorageClass' => $this->xmlValueOrNull($item->StorageClass, 'string'),
        'Owner' => new Owner([
        'DisplayName' => $this->xmlValueOrNull($item->Owner->DisplayName, 'string'),
        'ID' => $this->xmlValueOrNull($item->Owner->ID, 'string'),
        ]),
        ]);
        }
        $this->Name = $this->xmlValueOrNull($data->Name, 'string');
        $this->Prefix = $this->xmlValueOrNull($data->Prefix, 'string');
        $this->Delimiter = $this->xmlValueOrNull($data->Delimiter, 'string');
        $this->MaxKeys = $this->xmlValueOrNull($data->MaxKeys, 'int');
        $this->CommonPrefixes = [];
        foreach ($data->CommonPrefixes as $item) {
            $this->CommonPrefixes[] = new CommonPrefix([
        'Prefix' => $this->xmlValueOrNull($item->Prefix, 'string'),
        ]);
        }
        $this->EncodingType = $this->xmlValueOrNull($data->EncodingType, 'string');
    }
}
