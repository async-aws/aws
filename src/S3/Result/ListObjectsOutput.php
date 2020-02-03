<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ListObjectsOutput extends Result
{
    /**
     * A flag that indicates whether Amazon S3 returned all of the results that satisfied the search criteria.
     */
    private $IsTruncated;

    /**
     * Indicates where in the bucket listing begins. Marker is included in the response if it was sent with the request.
     */
    private $Marker;

    /**
     * When response is truncated (the IsTruncated element value in the response is true), you can use the key name in this
     * field as marker in the subsequent request to get next set of objects. Amazon S3 lists objects in alphabetical order
     * Note: This element is returned only if you have delimiter request parameter specified. If response does not include
     * the NextMaker and it is truncated, you can use the value of the last Key in the response as the marker in the
     * subsequent request to get the next set of object keys.
     */
    private $NextMarker;

    /**
     * Metadata about each object returned.
     */
    private $Contents = [];

    /**
     * Bucket name.
     */
    private $Name;

    /**
     * Keys that begin with the indicated prefix.
     */
    private $Prefix;

    /**
     * Causes keys that contain the same string between the prefix and the first occurrence of the delimiter to be rolled up
     * into a single result element in the `CommonPrefixes` collection. These rolled-up keys are not returned elsewhere in
     * the response. Each rolled-up result counts as only one return against the `MaxKeys` value.
     */
    private $Delimiter;

    /**
     * The maximum number of keys returned in the response body.
     */
    private $MaxKeys;

    /**
     * All of the keys rolled up in a common prefix count as a single return when calculating the number of returns.
     */
    private $CommonPrefixes = [];

    /**
     * Encoding type used by Amazon S3 to encode object keys in the response.
     */
    private $EncodingType;

    /**
     * @return CommonPrefix[]
     */
    public function getCommonPrefixes(): array
    {
        $this->initialize();

        return $this->CommonPrefixes;
    }

    /**
     * @return AwsObject[]
     */
    public function getContents(): array
    {
        $this->initialize();

        return $this->Contents;
    }

    public function getDelimiter(): ?string
    {
        $this->initialize();

        return $this->Delimiter;
    }

    public function getEncodingType(): ?string
    {
        $this->initialize();

        return $this->EncodingType;
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

    public function getMaxKeys(): ?int
    {
        $this->initialize();

        return $this->MaxKeys;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->Name;
    }

    public function getNextMarker(): ?string
    {
        $this->initialize();

        return $this->NextMarker;
    }

    public function getPrefix(): ?string
    {
        $this->initialize();

        return $this->Prefix;
    }

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
}
