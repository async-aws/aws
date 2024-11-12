<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\EncodingType;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Input\ListMultipartUploadsRequest;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\CommonPrefix;
use AsyncAws\S3\ValueObject\Initiator;
use AsyncAws\S3\ValueObject\MultipartUpload;
use AsyncAws\S3\ValueObject\Owner;

/**
 * @implements \IteratorAggregate<MultipartUpload|CommonPrefix>
 */
class ListMultipartUploadsOutput extends Result implements \IteratorAggregate
{
    /**
     * The name of the bucket to which the multipart upload was initiated. Does not return the access point ARN or access
     * point alias if used.
     *
     * @var string|null
     */
    private $bucket;

    /**
     * The key at or after which the listing began.
     *
     * @var string|null
     */
    private $keyMarker;

    /**
     * Together with key-marker, specifies the multipart upload after which listing should begin. If key-marker is not
     * specified, the upload-id-marker parameter is ignored. Otherwise, any multipart uploads for a key equal to the
     * key-marker might be included in the list only if they have an upload ID lexicographically greater than the specified
     * `upload-id-marker`.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $uploadIdMarker;

    /**
     * When a list is truncated, this element specifies the value that should be used for the key-marker request parameter
     * in a subsequent request.
     *
     * @var string|null
     */
    private $nextKeyMarker;

    /**
     * When a prefix is provided in the request, this field contains the specified prefix. The result contains only keys
     * starting with the specified prefix.
     *
     * > **Directory buckets** - For directory buckets, only prefixes that end in a delimiter (`/`) are supported.
     *
     * @var string|null
     */
    private $prefix;

    /**
     * Contains the delimiter you specified in the request. If you don't specify a delimiter in your request, this element
     * is absent from the response.
     *
     * > **Directory buckets** - For directory buckets, `/` is the only supported delimiter.
     *
     * @var string|null
     */
    private $delimiter;

    /**
     * When a list is truncated, this element specifies the value that should be used for the `upload-id-marker` request
     * parameter in a subsequent request.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $nextUploadIdMarker;

    /**
     * Maximum number of multipart uploads that could have been included in the response.
     *
     * @var int|null
     */
    private $maxUploads;

    /**
     * Indicates whether the returned list of multipart uploads is truncated. A value of true indicates that the list was
     * truncated. The list can be truncated if the number of multipart uploads exceeds the limit allowed or specified by max
     * uploads.
     *
     * @var bool|null
     */
    private $isTruncated;

    /**
     * Container for elements related to a particular multipart upload. A response can contain zero or more `Upload`
     * elements.
     *
     * @var MultipartUpload[]
     */
    private $uploads;

    /**
     * If you specify a delimiter in the request, then the result returns each distinct key prefix containing the delimiter
     * in a `CommonPrefixes` element. The distinct key prefixes are returned in the `Prefix` child element.
     *
     * > **Directory buckets** - For directory buckets, only prefixes that end in a delimiter (`/`) are supported.
     *
     * @var CommonPrefix[]
     */
    private $commonPrefixes;

    /**
     * Encoding type used by Amazon S3 to encode object keys in the response.
     *
     * If you specify the `encoding-type` request parameter, Amazon S3 includes this element in the response, and returns
     * encoded key name values in the following response elements:
     *
     * `Delimiter`, `KeyMarker`, `Prefix`, `NextKeyMarker`, `Key`.
     *
     * @var EncodingType::*|null
     */
    private $encodingType;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    public function getBucket(): ?string
    {
        $this->initialize();

        return $this->bucket;
    }

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
        if (!$this->input instanceof ListMultipartUploadsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setKeyMarker($page->nextKeyMarker);

                $input->setUploadIdMarker($page->nextUploadIdMarker);

                $this->registerPrefetch($nextPage = $client->listMultipartUploads($input));
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
     * Iterates over Uploads and CommonPrefixes.
     *
     * @return \Traversable<MultipartUpload|CommonPrefix>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMultipartUploadsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setKeyMarker($page->nextKeyMarker);

                $input->setUploadIdMarker($page->nextUploadIdMarker);

                $this->registerPrefetch($nextPage = $client->listMultipartUploads($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getUploads(true);
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

    public function getMaxUploads(): ?int
    {
        $this->initialize();

        return $this->maxUploads;
    }

    public function getNextKeyMarker(): ?string
    {
        $this->initialize();

        return $this->nextKeyMarker;
    }

    public function getNextUploadIdMarker(): ?string
    {
        $this->initialize();

        return $this->nextUploadIdMarker;
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

    public function getUploadIdMarker(): ?string
    {
        $this->initialize();

        return $this->uploadIdMarker;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<MultipartUpload>
     */
    public function getUploads(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->uploads;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMultipartUploadsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setKeyMarker($page->nextKeyMarker);

                $input->setUploadIdMarker($page->nextUploadIdMarker);

                $this->registerPrefetch($nextPage = $client->listMultipartUploads($input));
            } else {
                $nextPage = null;
            }

            yield from $page->uploads;

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
        $this->bucket = (null !== $v = $data->Bucket[0]) ? (string) $v : null;
        $this->keyMarker = (null !== $v = $data->KeyMarker[0]) ? (string) $v : null;
        $this->uploadIdMarker = (null !== $v = $data->UploadIdMarker[0]) ? (string) $v : null;
        $this->nextKeyMarker = (null !== $v = $data->NextKeyMarker[0]) ? (string) $v : null;
        $this->prefix = (null !== $v = $data->Prefix[0]) ? (string) $v : null;
        $this->delimiter = (null !== $v = $data->Delimiter[0]) ? (string) $v : null;
        $this->nextUploadIdMarker = (null !== $v = $data->NextUploadIdMarker[0]) ? (string) $v : null;
        $this->maxUploads = (null !== $v = $data->MaxUploads[0]) ? (int) (string) $v : null;
        $this->isTruncated = (null !== $v = $data->IsTruncated[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->uploads = (0 === ($v = $data->Upload)->count()) ? [] : $this->populateResultMultipartUploadList($v);
        $this->commonPrefixes = (0 === ($v = $data->CommonPrefixes)->count()) ? [] : $this->populateResultCommonPrefixList($v);
        $this->encodingType = (null !== $v = $data->EncodingType[0]) ? (string) $v : null;
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

    private function populateResultInitiator(\SimpleXMLElement $xml): Initiator
    {
        return new Initiator([
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
            'DisplayName' => (null !== $v = $xml->DisplayName[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultMultipartUpload(\SimpleXMLElement $xml): MultipartUpload
    {
        return new MultipartUpload([
            'UploadId' => (null !== $v = $xml->UploadId[0]) ? (string) $v : null,
            'Key' => (null !== $v = $xml->Key[0]) ? (string) $v : null,
            'Initiated' => (null !== $v = $xml->Initiated[0]) ? new \DateTimeImmutable((string) $v) : null,
            'StorageClass' => (null !== $v = $xml->StorageClass[0]) ? (string) $v : null,
            'Owner' => 0 === $xml->Owner->count() ? null : $this->populateResultOwner($xml->Owner),
            'Initiator' => 0 === $xml->Initiator->count() ? null : $this->populateResultInitiator($xml->Initiator),
            'ChecksumAlgorithm' => (null !== $v = $xml->ChecksumAlgorithm[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return MultipartUpload[]
     */
    private function populateResultMultipartUploadList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultMultipartUpload($item);
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
}
