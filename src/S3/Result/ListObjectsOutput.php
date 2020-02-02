<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ListObjectsOutput extends Result
{
    private $IsTruncated;

    private $Marker;

    private $NextMarker;

    private $Contents = [];

    private $Name;

    private $Prefix;

    private $Delimiter;

    private $MaxKeys;

    private $CommonPrefixes = [];

    private $EncodingType;

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
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

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->IsTruncated;
    }

    public function getMarker(): ?string
    {
        $this->initialize();

        return $this->Marker;
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->NextMarker;
    }

    /**
     * @return AwsObject[]
     */
    public function getContents(): array
    {
        $this->initialize();

        return $this->Contents;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->Name;
    }

    public function getPrefix(): ?string
    {
        $this->initialize();

        return $this->Prefix;
    }

    public function getDelimiter(): ?string
    {
        $this->initialize();

        return $this->Delimiter;
    }

    public function getMaxKeys(): ?int
    {
        $this->initialize();

        return $this->MaxKeys;
    }

    /**
     * @return CommonPrefix[]
     */
    public function getCommonPrefixes(): array
    {
        $this->initialize();

        return $this->CommonPrefixes;
    }

    public function getEncodingType(): ?string
    {
        $this->initialize();

        return $this->EncodingType;
    }
}
