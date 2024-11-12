<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\EncodingType;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Input\ListObjectVersionsRequest;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\CommonPrefix;
use AsyncAws\S3\ValueObject\DeleteMarkerEntry;
use AsyncAws\S3\ValueObject\ObjectVersion;
use AsyncAws\S3\ValueObject\Owner;
use AsyncAws\S3\ValueObject\RestoreStatus;

/**
 * @implements \IteratorAggregate<ObjectVersion|DeleteMarkerEntry|CommonPrefix>
 */
class ListObjectVersionsOutput extends Result implements \IteratorAggregate
{
    /**
     * A flag that indicates whether Amazon S3 returned all of the results that satisfied the search criteria. If your
     * results were truncated, you can make a follow-up paginated request by using the `NextKeyMarker` and
     * `NextVersionIdMarker` response parameters as a starting place in another request to return the rest of the results.
     *
     * @var bool|null
     */
    private $isTruncated;

    /**
     * Marks the last key returned in a truncated response.
     *
     * @var string|null
     */
    private $keyMarker;

    /**
     * Marks the last version of the key returned in a truncated response.
     *
     * @var string|null
     */
    private $versionIdMarker;

    /**
     * When the number of responses exceeds the value of `MaxKeys`, `NextKeyMarker` specifies the first key not returned
     * that satisfies the search criteria. Use this value for the key-marker request parameter in a subsequent request.
     *
     * @var string|null
     */
    private $nextKeyMarker;

    /**
     * When the number of responses exceeds the value of `MaxKeys`, `NextVersionIdMarker` specifies the first object version
     * not returned that satisfies the search criteria. Use this value for the `version-id-marker` request parameter in a
     * subsequent request.
     *
     * @var string|null
     */
    private $nextVersionIdMarker;

    /**
     * Container for version information.
     *
     * @var ObjectVersion[]
     */
    private $versions;

    /**
     * Container for an object that is a delete marker.
     *
     * @var DeleteMarkerEntry[]
     */
    private $deleteMarkers;

    /**
     * The bucket name.
     *
     * @var string|null
     */
    private $name;

    /**
     * Selects objects that start with the value supplied by this parameter.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * The delimiter grouping the included keys. A delimiter is a character that you specify to group keys. All keys that
     * contain the same string between the prefix and the first occurrence of the delimiter are grouped under a single
     * result element in `CommonPrefixes`. These groups are counted as one result against the `max-keys` limitation. These
     * keys are not returned elsewhere in the response.
     *
     * @var string|null
     */
    private $delimiter;

    /**
     * Specifies the maximum number of objects to return.
     *
     * @var int|null
     */
    private $maxKeys;

    /**
     * All of the keys rolled up into a common prefix count as a single return when calculating the number of returns.
     *
     * @var CommonPrefix[]
     */
    private $commonPrefixes;

    /**
     * Encoding type used by Amazon S3 to encode object key names in the XML response.
     *
     * If you specify the `encoding-type` request parameter, Amazon S3 includes this element in the response, and returns
     * encoded key name values in the following response elements:
     *
     * `KeyMarker, NextKeyMarker, Prefix, Key`, and `Delimiter`.
     *
     * @var EncodingType::*|null
     */
    private $encodingType;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<CommonPrefix>
     */
    public function getCommonPrefixes(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->commonPrefixes;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListObjectVersionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setKeyMarker($page->nextKeyMarker);

                $input->setVersionIdMarker($page->nextVersionIdMarker);

                $this->registerPrefetch($nextPage = $client->listObjectVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->commonPrefixes;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<DeleteMarkerEntry>
     */
    public function getDeleteMarkers(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->deleteMarkers;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListObjectVersionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setKeyMarker($page->nextKeyMarker);

                $input->setVersionIdMarker($page->nextVersionIdMarker);

                $this->registerPrefetch($nextPage = $client->listObjectVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->deleteMarkers;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getDelimiter(): ?string
    {
        $this->initialize();

        return $this->delimiter;
    }

    /**
     * @return EncodingType::*|null
     */
    public function getEncodingType(): ?string
    {
        $this->initialize();

        return $this->encodingType;
    }

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->isTruncated;
    }

    /**
     * Iterates over Versions and DeleteMarkers and CommonPrefixes.
     *
     * @return \Traversable<ObjectVersion|DeleteMarkerEntry|CommonPrefix>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListObjectVersionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setKeyMarker($page->nextKeyMarker);

                $input->setVersionIdMarker($page->nextVersionIdMarker);

                $this->registerPrefetch($nextPage = $client->listObjectVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getVersions(true);
            yield from $page->getDeleteMarkers(true);
            yield from $page->getCommonPrefixes(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getKeyMarker(): ?string
    {
        $this->initialize();

        return $this->keyMarker;
    }

    public function getMaxKeys(): ?int
    {
        $this->initialize();

        return $this->maxKeys;
    }

    public function getName(): ?string
    {
        $this->initialize();

        return $this->name;
    }

    public function getNextKeyMarker(): ?string
    {
        $this->initialize();

        return $this->nextKeyMarker;
    }

    public function getNextVersionIdMarker(): ?string
    {
        $this->initialize();

        return $this->nextVersionIdMarker;
    }

    public function getPrefix(): ?string
    {
        $this->initialize();

        return $this->prefix;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->requestCharged;
    }

    public function getVersionIdMarker(): ?string
    {
        $this->initialize();

        return $this->versionIdMarker;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<ObjectVersion>
     */
    public function getVersions(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->versions;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListObjectVersionsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setKeyMarker($page->nextKeyMarker);

                $input->setVersionIdMarker($page->nextVersionIdMarker);

                $this->registerPrefetch($nextPage = $client->listObjectVersions($input));
            } else {
                $nextPage = null;
            }

            yield from $page->versions;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->isTruncated = (null !== $v = $data->IsTruncated[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->keyMarker = (null !== $v = $data->KeyMarker[0]) ? (string) $v : null;
        $this->versionIdMarker = (null !== $v = $data->VersionIdMarker[0]) ? (string) $v : null;
        $this->nextKeyMarker = (null !== $v = $data->NextKeyMarker[0]) ? (string) $v : null;
        $this->nextVersionIdMarker = (null !== $v = $data->NextVersionIdMarker[0]) ? (string) $v : null;
        $this->versions = (0 === ($v = $data->Version)->count()) ? [] : $this->populateResultObjectVersionList($v);
        $this->deleteMarkers = (0 === ($v = $data->DeleteMarker)->count()) ? [] : $this->populateResultDeleteMarkers($v);
        $this->name = (null !== $v = $data->Name[0]) ? (string) $v : null;
        $this->prefix = (null !== $v = $data->Prefix[0]) ? (string) $v : null;
        $this->delimiter = (null !== $v = $data->Delimiter[0]) ? (string) $v : null;
        $this->maxKeys = (null !== $v = $data->MaxKeys[0]) ? (int) (string) $v : null;
        $this->commonPrefixes = (0 === ($v = $data->CommonPrefixes)->count()) ? [] : $this->populateResultCommonPrefixList($v);
        $this->encodingType = (null !== $v = $data->EncodingType[0]) ? (string) $v : null;
    }

    /**
     * @return list<ChecksumAlgorithm::*>
     */
    private function populateResultChecksumAlgorithmList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = (string) $item;
        }

        return $items;
    }

    private function populateResultCommonPrefix(\SimpleXMLElement $xml): CommonPrefix
    {
        return new CommonPrefix([
            'Prefix' => (null !== $v = $xml->Prefix[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return CommonPrefix[]
     */
    private function populateResultCommonPrefixList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultCommonPrefix($item);
        }

        return $items;
    }

    private function populateResultDeleteMarkerEntry(\SimpleXMLElement $xml): DeleteMarkerEntry
    {
        return new DeleteMarkerEntry([
            'Owner' => 0 === $xml->Owner->count() ? null : $this->populateResultOwner($xml->Owner),
            'Key' => (null !== $v = $xml->Key[0]) ? (string) $v : null,
            'VersionId' => (null !== $v = $xml->VersionId[0]) ? (string) $v : null,
            'IsLatest' => (null !== $v = $xml->IsLatest[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'LastModified' => (null !== $v = $xml->LastModified[0]) ? new \DateTimeImmutable((string) $v) : null,
        ]);
    }

    /**
     * @return DeleteMarkerEntry[]
     */
    private function populateResultDeleteMarkers(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultDeleteMarkerEntry($item);
        }

        return $items;
    }

    private function populateResultObjectVersion(\SimpleXMLElement $xml): ObjectVersion
    {
        return new ObjectVersion([
            'ETag' => (null !== $v = $xml->ETag[0]) ? (string) $v : null,
            'ChecksumAlgorithm' => (0 === ($v = $xml->ChecksumAlgorithm)->count()) ? null : $this->populateResultChecksumAlgorithmList($v),
            'Size' => (null !== $v = $xml->Size[0]) ? (int) (string) $v : null,
            'StorageClass' => (null !== $v = $xml->StorageClass[0]) ? (string) $v : null,
            'Key' => (null !== $v = $xml->Key[0]) ? (string) $v : null,
            'VersionId' => (null !== $v = $xml->VersionId[0]) ? (string) $v : null,
            'IsLatest' => (null !== $v = $xml->IsLatest[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'LastModified' => (null !== $v = $xml->LastModified[0]) ? new \DateTimeImmutable((string) $v) : null,
            'Owner' => 0 === $xml->Owner->count() ? null : $this->populateResultOwner($xml->Owner),
            'RestoreStatus' => 0 === $xml->RestoreStatus->count() ? null : $this->populateResultRestoreStatus($xml->RestoreStatus),
        ]);
    }

    /**
     * @return ObjectVersion[]
     */
    private function populateResultObjectVersionList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultObjectVersion($item);
        }

        return $items;
    }

    private function populateResultOwner(\SimpleXMLElement $xml): Owner
    {
        return new Owner([
            'DisplayName' => (null !== $v = $xml->DisplayName[0]) ? (string) $v : null,
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultRestoreStatus(\SimpleXMLElement $xml): RestoreStatus
    {
        return new RestoreStatus([
            'IsRestoreInProgress' => (null !== $v = $xml->IsRestoreInProgress[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
            'RestoreExpiryDate' => (null !== $v = $xml->RestoreExpiryDate[0]) ? new \DateTimeImmutable((string) $v) : null,
        ]);
    }
}
