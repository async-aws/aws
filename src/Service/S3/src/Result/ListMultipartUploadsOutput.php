<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\EncodingType;
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
     * Name of the bucket to which the multipart upload was initiated.
     */
    private $Bucket;

    /**
     * The key at or after which the listing began.
     */
    private $KeyMarker;

    /**
     * Upload ID after which listing began.
     */
    private $UploadIdMarker;

    /**
     * When a list is truncated, this element specifies the value that should be used for the key-marker request parameter
     * in a subsequent request.
     */
    private $NextKeyMarker;

    /**
     * When a prefix is provided in the request, this field contains the specified prefix. The result contains only keys
     * starting with the specified prefix.
     */
    private $Prefix;

    /**
     * Contains the delimiter you specified in the request. If you don't specify a delimiter in your request, this element
     * is absent from the response.
     */
    private $Delimiter;

    /**
     * When a list is truncated, this element specifies the value that should be used for the `upload-id-marker` request
     * parameter in a subsequent request.
     */
    private $NextUploadIdMarker;

    /**
     * Maximum number of multipart uploads that could have been included in the response.
     */
    private $MaxUploads;

    /**
     * Indicates whether the returned list of multipart uploads is truncated. A value of true indicates that the list was
     * truncated. The list can be truncated if the number of multipart uploads exceeds the limit allowed or specified by max
     * uploads.
     */
    private $IsTruncated;

    /**
     * Container for elements related to a particular multipart upload. A response can contain zero or more `Upload`
     * elements.
     */
    private $Uploads = [];

    /**
     * If you specify a delimiter in the request, then the result returns each distinct key prefix containing the delimiter
     * in a `CommonPrefixes` element. The distinct key prefixes are returned in the `Prefix` child element.
     */
    private $CommonPrefixes = [];

    /**
     * Encoding type used by Amazon S3 to encode object keys in the response.
     */
    private $EncodingType;

    public function getBucket(): ?string
    {
        $this->initialize();

        return $this->Bucket;
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
            yield from $this->CommonPrefixes;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMultipartUploadsRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getIsTruncated()) {
                $input->setKeyMarker($page->getNextKeyMarker());

                $input->setUploadIdMarker($page->getNextUploadIdMarker());

                $this->registerPrefetch($nextPage = $client->ListMultipartUploads($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getCommonPrefixes(true);

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

        return $this->Delimiter;
    }

    /**
     * @return EncodingType::*|null
     */
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

    /**
     * Iterates over Uploads then CommonPrefixes.
     *
     * @return \Traversable<MultipartUpload|CommonPrefix>
     */
    public function getIterator(): \Traversable
    {
        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMultipartUploadsRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getIsTruncated()) {
                $input->setKeyMarker($page->getNextKeyMarker());

                $input->setUploadIdMarker($page->getNextUploadIdMarker());

                $this->registerPrefetch($nextPage = $client->ListMultipartUploads($input));
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

        return $this->KeyMarker;
    }

    public function getMaxUploads(): ?int
    {
        $this->initialize();

        return $this->MaxUploads;
    }

    public function getNextKeyMarker(): ?string
    {
        $this->initialize();

        return $this->NextKeyMarker;
    }

    public function getNextUploadIdMarker(): ?string
    {
        $this->initialize();

        return $this->NextUploadIdMarker;
    }

    public function getPrefix(): ?string
    {
        $this->initialize();

        return $this->Prefix;
    }

    public function getUploadIdMarker(): ?string
    {
        $this->initialize();

        return $this->UploadIdMarker;
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
            yield from $this->Uploads;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new \InvalidArgumentException('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListMultipartUploadsRequest) {
            throw new \InvalidArgumentException('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            if ($page->getIsTruncated()) {
                $input->setKeyMarker($page->getNextKeyMarker());

                $input->setUploadIdMarker($page->getNextUploadIdMarker());

                $this->registerPrefetch($nextPage = $client->ListMultipartUploads($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getUploads(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $this->Bucket = ($v = $data->Bucket) ? (string) $v : null;
        $this->KeyMarker = ($v = $data->KeyMarker) ? (string) $v : null;
        $this->UploadIdMarker = ($v = $data->UploadIdMarker) ? (string) $v : null;
        $this->NextKeyMarker = ($v = $data->NextKeyMarker) ? (string) $v : null;
        $this->Prefix = ($v = $data->Prefix) ? (string) $v : null;
        $this->Delimiter = ($v = $data->Delimiter) ? (string) $v : null;
        $this->NextUploadIdMarker = ($v = $data->NextUploadIdMarker) ? (string) $v : null;
        $this->MaxUploads = ($v = $data->MaxUploads) ? (int) (string) $v : null;
        $this->IsTruncated = ($v = $data->IsTruncated) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->Uploads = !$data->Upload ? [] : $this->populateResultMultipartUploadList($data->Upload);
        $this->CommonPrefixes = !$data->CommonPrefixes ? [] : $this->populateResultCommonPrefixList($data->CommonPrefixes);
        $this->EncodingType = ($v = $data->EncodingType) ? (string) $v : null;
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
     * @return MultipartUpload[]
     */
    private function populateResultMultipartUploadList(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new MultipartUpload([
                'UploadId' => ($v = $item->UploadId) ? (string) $v : null,
                'Key' => ($v = $item->Key) ? (string) $v : null,
                'Initiated' => ($v = $item->Initiated) ? new \DateTimeImmutable((string) $v) : null,
                'StorageClass' => ($v = $item->StorageClass) ? (string) $v : null,
                'Owner' => !$item->Owner ? null : new Owner([
                    'DisplayName' => ($v = $item->Owner->DisplayName) ? (string) $v : null,
                    'ID' => ($v = $item->Owner->ID) ? (string) $v : null,
                ]),
                'Initiator' => !$item->Initiator ? null : new Initiator([
                    'ID' => ($v = $item->Initiator->ID) ? (string) $v : null,
                    'DisplayName' => ($v = $item->Initiator->DisplayName) ? (string) $v : null,
                ]),
            ]);
        }

        return $items;
    }
}
