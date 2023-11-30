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
        $this->isTruncated = ($v = $data->IsTruncated) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->keyMarker = ($v = $data->KeyMarker) ? (string) $v : null;
        $this->versionIdMarker = ($v = $data->VersionIdMarker) ? (string) $v : null;
        $this->nextKeyMarker = ($v = $data->NextKeyMarker) ? (string) $v : null;
        $this->nextVersionIdMarker = ($v = $data->NextVersionIdMarker) ? (string) $v : null;
        $this->versions = !$data->Version ? [] : $this->populateResultObjectVersionList($data->Version);
        $this->deleteMarkers = !$data->DeleteMarker ? [] : $this->populateResultDeleteMarkers($data->DeleteMarker);
        $this->name = ($v = $data->Name) ? (string) $v : null;
        $this->prefix = ($v = $data->Prefix) ? (string) $v : null;
        $this->delimiter = ($v = $data->Delimiter) ? (string) $v : null;
        $this->maxKeys = ($v = $data->MaxKeys) ? (int) (string) $v : null;
        $this->commonPrefixes = !$data->CommonPrefixes ? [] : $this->populateResultCommonPrefixList($data->CommonPrefixes);
        $this->encodingType = ($v = $data->EncodingType) ? (string) $v : null;
    }

    /**
     * @return list<ChecksumAlgorithm::*>
     */
    private function populateResultChecksumAlgorithmList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $a = ($v = $item) ? (string) $v : null;
            if (null !== $a) {
                $items[] = $a;
            }
        }

        return $items;
    }

    /**
     * @return CommonPrefix[]
     */
    private function populateResultCommonPrefixList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new CommonPrefix([
                'Prefix' => ($v = $item->Prefix) ? (string) $v : null,
            ]);
        }

        return $items;
    }

    /**
     * @return DeleteMarkerEntry[]
     */
    private function populateResultDeleteMarkers(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new DeleteMarkerEntry([
                'Owner' => !$item->Owner ? null : new Owner([
                    'DisplayName' => ($v = $item->Owner->DisplayName) ? (string) $v : null,
                    'ID' => ($v = $item->Owner->ID) ? (string) $v : null,
                ]),
                'Key' => ($v = $item->Key) ? (string) $v : null,
                'VersionId' => ($v = $item->VersionId) ? (string) $v : null,
                'IsLatest' => ($v = $item->IsLatest) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'LastModified' => ($v = $item->LastModified) ? new \DateTimeImmutable((string) $v) : null,
            ]);
        }

        return $items;
    }

    /**
     * @return ObjectVersion[]
     */
    private function populateResultObjectVersionList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new ObjectVersion([
                'ETag' => ($v = $item->ETag) ? (string) $v : null,
                'ChecksumAlgorithm' => !$item->ChecksumAlgorithm ? null : $this->populateResultChecksumAlgorithmList($item->ChecksumAlgorithm),
                'Size' => ($v = $item->Size) ? (int) (string) $v : null,
                'StorageClass' => ($v = $item->StorageClass) ? (string) $v : null,
                'Key' => ($v = $item->Key) ? (string) $v : null,
                'VersionId' => ($v = $item->VersionId) ? (string) $v : null,
                'IsLatest' => ($v = $item->IsLatest) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                'LastModified' => ($v = $item->LastModified) ? new \DateTimeImmutable((string) $v) : null,
                'Owner' => !$item->Owner ? null : new Owner([
                    'DisplayName' => ($v = $item->Owner->DisplayName) ? (string) $v : null,
                    'ID' => ($v = $item->Owner->ID) ? (string) $v : null,
                ]),
                'RestoreStatus' => !$item->RestoreStatus ? null : new RestoreStatus([
                    'IsRestoreInProgress' => ($v = $item->RestoreStatus->IsRestoreInProgress) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null,
                    'RestoreExpiryDate' => ($v = $item->RestoreStatus->RestoreExpiryDate) ? new \DateTimeImmutable((string) $v) : null,
                ]),
            ]);
        }

        return $items;
    }
}
