<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\EncodingType;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Input\ListObjectsV2Request;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\AwsObject;
use AsyncAws\S3\ValueObject\CommonPrefix;
use AsyncAws\S3\ValueObject\Owner;
use AsyncAws\S3\ValueObject\RestoreStatus;

/**
 * @implements \IteratorAggregate<AwsObject|CommonPrefix>
 */
class ListObjectsV2Output extends Result implements \IteratorAggregate
{
    /**
     * Set to `false` if all of the results were returned. Set to `true` if more keys are available to return. If the number
     * of results exceeds that specified by `MaxKeys`, all of the results might not be returned.
     *
     * @var bool|null
     */
    private $isTruncated;

    /**
     * Metadata about each object returned.
     *
     * @var AwsObject[]
     */
    private $contents;

    /**
     * The bucket name.
     *
     * @var string|null
     */
    private $name;

    /**
     * Keys that begin with the indicated prefix.
     *
     * > **Directory buckets** - For directory buckets, only prefixes that end in a delimiter (`/`) are supported.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * Causes keys that contain the same string between the `prefix` and the first occurrence of the delimiter to be rolled
     * up into a single result element in the `CommonPrefixes` collection. These rolled-up keys are not returned elsewhere
     * in the response. Each rolled-up result counts as only one return against the `MaxKeys` value.
     *
     * > **Directory buckets** - For directory buckets, `/` is the only supported delimiter.
     *
     * @var string|null
     */
    private $delimiter;

    /**
     * Sets the maximum number of keys returned in the response. By default, the action returns up to 1,000 key names. The
     * response might contain fewer keys but will never contain more.
     *
     * @var int|null
     */
    private $maxKeys;

    /**
     * All of the keys (up to 1,000) that share the same prefix are grouped together. When counting the total numbers of
     * returns by this API operation, this group of keys is considered as one item.
     *
     * A response can contain `CommonPrefixes` only if you specify a delimiter.
     *
     * `CommonPrefixes` contains all (if there are any) keys between `Prefix` and the next occurrence of the string
     * specified by a delimiter.
     *
     * `CommonPrefixes` lists keys that act like subdirectories in the directory specified by `Prefix`.
     *
     * For example, if the prefix is `notes/` and the delimiter is a slash (`/`) as in `notes/summer/july`, the common
     * prefix is `notes/summer/`. All of the keys that roll up into a common prefix count as a single return when
     * calculating the number of returns.
     *
     * > - **Directory buckets** - For directory buckets, only prefixes that end in a delimiter (`/`) are supported.
     * > - **Directory buckets ** - When you query `ListObjectsV2` with a delimiter during in-progress multipart uploads,
     * >   the `CommonPrefixes` response parameter contains the prefixes that are associated with the in-progress multipart
     * >   uploads. For more information about multipart uploads, see Multipart Upload Overview [^1] in the *Amazon S3 User
     * >   Guide*.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html
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
     * `Delimiter, Prefix, Key,` and `StartAfter`.
     *
     * @var EncodingType::*|null
     */
    private $encodingType;

    /**
     * `KeyCount` is the number of keys returned with this request. `KeyCount` will always be less than or equal to the
     * `MaxKeys` field. For example, if you ask for 50 keys, your result will include 50 keys or fewer.
     *
     * @var int|null
     */
    private $keyCount;

    /**
     * If `ContinuationToken` was sent with the request, it is included in the response. You can use the returned
     * `ContinuationToken` for pagination of the list response. You can use this `ContinuationToken` for pagination of the
     * list results.
     *
     * @var string|null
     */
    private $continuationToken;

    /**
     * `NextContinuationToken` is sent when `isTruncated` is true, which means there are more keys in the bucket that can be
     * listed. The next list requests to Amazon S3 can be continued with this `NextContinuationToken`.
     * `NextContinuationToken` is obfuscated and is not a real key.
     *
     * @var string|null
     */
    private $nextContinuationToken;

    /**
     * If StartAfter was sent with the request, it is included in the response.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $startAfter;

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
        if (!$this->input instanceof ListObjectsV2Request) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextContinuationToken) {
                $input->setContinuationToken($page->nextContinuationToken);

                $this->registerPrefetch($nextPage = $client->listObjectsV2($input));
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
     * @return iterable<AwsObject>
     */
    public function getContents(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->contents;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListObjectsV2Request) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextContinuationToken) {
                $input->setContinuationToken($page->nextContinuationToken);

                $this->registerPrefetch($nextPage = $client->listObjectsV2($input));
            } else {
                $nextPage = null;
            }

            yield from $page->contents;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getContinuationToken(): ?string
    {
        $this->initialize();

        return $this->continuationToken;
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
     * Iterates over Contents and CommonPrefixes.
     *
     * @return \Traversable<AwsObject|CommonPrefix>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListObjectsV2Request) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if (null !== $page->nextContinuationToken) {
                $input->setContinuationToken($page->nextContinuationToken);

                $this->registerPrefetch($nextPage = $client->listObjectsV2($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getContents(true);
            yield from $page->getCommonPrefixes(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getKeyCount(): ?int
    {
        $this->initialize();

        return $this->keyCount;
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

    public function getNextContinuationToken(): ?string
    {
        $this->initialize();

        return $this->nextContinuationToken;
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

    public function getStartAfter(): ?string
    {
        $this->initialize();

        return $this->startAfter;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->isTruncated = (null !== $v = $data->IsTruncated[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->contents = (0 === ($v = $data->Contents)->count()) ? [] : $this->populateResultObjectList($v);
        $this->name = (null !== $v = $data->Name[0]) ? (string) $v : null;
        $this->prefix = (null !== $v = $data->Prefix[0]) ? (string) $v : null;
        $this->delimiter = (null !== $v = $data->Delimiter[0]) ? (string) $v : null;
        $this->maxKeys = (null !== $v = $data->MaxKeys[0]) ? (int) (string) $v : null;
        $this->commonPrefixes = (0 === ($v = $data->CommonPrefixes)->count()) ? [] : $this->populateResultCommonPrefixList($v);
        $this->encodingType = (null !== $v = $data->EncodingType[0]) ? (string) $v : null;
        $this->keyCount = (null !== $v = $data->KeyCount[0]) ? (int) (string) $v : null;
        $this->continuationToken = (null !== $v = $data->ContinuationToken[0]) ? (string) $v : null;
        $this->nextContinuationToken = (null !== $v = $data->NextContinuationToken[0]) ? (string) $v : null;
        $this->startAfter = (null !== $v = $data->StartAfter[0]) ? (string) $v : null;
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

    private function populateResultObject(\SimpleXMLElement $xml): AwsObject
    {
        return new AwsObject([
            'Key' => (null !== $v = $xml->Key[0]) ? (string) $v : null,
            'LastModified' => (null !== $v = $xml->LastModified[0]) ? new \DateTimeImmutable((string) $v) : null,
            'ETag' => (null !== $v = $xml->ETag[0]) ? (string) $v : null,
            'ChecksumAlgorithm' => (0 === ($v = $xml->ChecksumAlgorithm)->count()) ? null : $this->populateResultChecksumAlgorithmList($v),
            'Size' => (null !== $v = $xml->Size[0]) ? (int) (string) $v : null,
            'StorageClass' => (null !== $v = $xml->StorageClass[0]) ? (string) $v : null,
            'Owner' => 0 === $xml->Owner->count() ? null : $this->populateResultOwner($xml->Owner),
            'RestoreStatus' => 0 === $xml->RestoreStatus->count() ? null : $this->populateResultRestoreStatus($xml->RestoreStatus),
        ]);
    }

    /**
     * @return AwsObject[]
     */
    private function populateResultObjectList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultObject($item);
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
