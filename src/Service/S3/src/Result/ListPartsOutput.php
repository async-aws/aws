<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\StorageClass;
use AsyncAws\S3\Input\ListPartsRequest;
use AsyncAws\S3\S3Client;
use AsyncAws\S3\ValueObject\Initiator;
use AsyncAws\S3\ValueObject\Owner;
use AsyncAws\S3\ValueObject\Part;

/**
 * @implements \IteratorAggregate<Part>
 */
class ListPartsOutput extends Result implements \IteratorAggregate
{
    /**
     * If the bucket has a lifecycle rule configured with an action to abort incomplete multipart uploads and the prefix in
     * the lifecycle rule matches the object name in the request, then the response includes this header indicating when the
     * initiated multipart upload will become eligible for abort operation. For more information, see Aborting Incomplete
     * Multipart Uploads Using a Bucket Lifecycle Configuration [^1].
     *
     * The response will also include the `x-amz-abort-rule-id` header that will provide the ID of the lifecycle
     * configuration rule that defines this action.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html#mpu-abort-incomplete-mpu-lifecycle-config
     *
     * @var \DateTimeImmutable|null
     */
    private $abortDate;

    /**
     * This header is returned along with the `x-amz-abort-date` header. It identifies applicable lifecycle configuration
     * rule that defines the action to abort incomplete multipart uploads.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $abortRuleId;

    /**
     * The name of the bucket to which the multipart upload was initiated. Does not return the access point ARN or access
     * point alias if used.
     *
     * @var string|null
     */
    private $bucket;

    /**
     * Object key for which the multipart upload was initiated.
     *
     * @var string|null
     */
    private $key;

    /**
     * Upload ID identifying the multipart upload whose parts are being listed.
     *
     * @var string|null
     */
    private $uploadId;

    /**
     * Specifies the part after which listing should begin. Only parts with higher part numbers will be listed.
     *
     * @var int|null
     */
    private $partNumberMarker;

    /**
     * When a list is truncated, this element specifies the last part in the list, as well as the value to use for the
     * `part-number-marker` request parameter in a subsequent request.
     *
     * @var int|null
     */
    private $nextPartNumberMarker;

    /**
     * Maximum number of parts that were allowed in the response.
     *
     * @var int|null
     */
    private $maxParts;

    /**
     * Indicates whether the returned list of parts is truncated. A true value indicates that the list was truncated. A list
     * can be truncated if the number of parts exceeds the limit returned in the MaxParts element.
     *
     * @var bool|null
     */
    private $isTruncated;

    /**
     * Container for elements related to a particular part. A response can contain zero or more `Part` elements.
     *
     * @var Part[]
     */
    private $parts;

    /**
     * Container element that identifies who initiated the multipart upload. If the initiator is an Amazon Web Services
     * account, this element provides the same information as the `Owner` element. If the initiator is an IAM User, this
     * element provides the user ARN and display name.
     *
     * @var Initiator|null
     */
    private $initiator;

    /**
     * Container element that identifies the object owner, after the object is created. If multipart upload is initiated by
     * an IAM user, this element provides the parent account ID and display name.
     *
     * > **Directory buckets** - The bucket owner is returned as the object owner for all the parts.
     *
     * @var Owner|null
     */
    private $owner;

    /**
     * The class of storage used to store the uploaded object.
     *
     * > **Directory buckets** - Only the S3 Express One Zone storage class is supported by directory buckets to store
     * > objects.
     *
     * @var StorageClass::*|null
     */
    private $storageClass;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    /**
     * The algorithm that was used to create a checksum of the object.
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    public function getAbortDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->abortDate;
    }

    public function getAbortRuleId(): ?string
    {
        $this->initialize();

        return $this->abortRuleId;
    }

    public function getBucket(): ?string
    {
        $this->initialize();

        return $this->bucket;
    }

    /**
     * @return ChecksumAlgorithm::*|null
     */
    public function getChecksumAlgorithm(): ?string
    {
        $this->initialize();

        return $this->checksumAlgorithm;
    }

    public function getInitiator(): ?Initiator
    {
        $this->initialize();

        return $this->initiator;
    }

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->isTruncated;
    }

    /**
     * Iterates over Parts.
     *
     * @return \Traversable<Part>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getParts();
    }

    public function getKey(): ?string
    {
        $this->initialize();

        return $this->key;
    }

    public function getMaxParts(): ?int
    {
        $this->initialize();

        return $this->maxParts;
    }

    public function getNextPartNumberMarker(): ?int
    {
        $this->initialize();

        return $this->nextPartNumberMarker;
    }

    public function getOwner(): ?Owner
    {
        $this->initialize();

        return $this->owner;
    }

    public function getPartNumberMarker(): ?int
    {
        $this->initialize();

        return $this->partNumberMarker;
    }

    /**
     * @param bool $currentPageOnly When true, iterates over items of the current page. Otherwise also fetch items in the next pages.
     *
     * @return iterable<Part>
     */
    public function getParts(bool $currentPageOnly = false): iterable
    {
        if ($currentPageOnly) {
            $this->initialize();
            yield from $this->parts;

            return;
        }

        $client = $this->awsClient;
        if (!$client instanceof S3Client) {
            throw new InvalidArgument('missing client injected in paginated result');
        }
        if (!$this->input instanceof ListPartsRequest) {
            throw new InvalidArgument('missing last request injected in paginated result');
        }
        $input = clone $this->input;
        $page = $this;
        while (true) {
            $page->initialize();
            if ($page->isTruncated) {
                $input->setPartNumberMarker($page->nextPartNumberMarker);

                $this->registerPrefetch($nextPage = $client->listParts($input));
            } else {
                $nextPage = null;
            }

            yield from $page->parts;

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->requestCharged;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        $this->initialize();

        return $this->storageClass;
    }

    public function getUploadId(): ?string
    {
        $this->initialize();

        return $this->uploadId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->abortDate = isset($headers['x-amz-abort-date'][0]) ? new \DateTimeImmutable($headers['x-amz-abort-date'][0]) : null;
        $this->abortRuleId = $headers['x-amz-abort-rule-id'][0] ?? null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->bucket = (null !== $v = $data->Bucket[0]) ? (string) $v : null;
        $this->key = (null !== $v = $data->Key[0]) ? (string) $v : null;
        $this->uploadId = (null !== $v = $data->UploadId[0]) ? (string) $v : null;
        $this->partNumberMarker = (null !== $v = $data->PartNumberMarker[0]) ? (int) (string) $v : null;
        $this->nextPartNumberMarker = (null !== $v = $data->NextPartNumberMarker[0]) ? (int) (string) $v : null;
        $this->maxParts = (null !== $v = $data->MaxParts[0]) ? (int) (string) $v : null;
        $this->isTruncated = (null !== $v = $data->IsTruncated[0]) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->parts = (0 === ($v = $data->Part)->count()) ? [] : $this->populateResultParts($v);
        $this->initiator = 0 === $data->Initiator->count() ? null : $this->populateResultInitiator($data->Initiator);
        $this->owner = 0 === $data->Owner->count() ? null : $this->populateResultOwner($data->Owner);
        $this->storageClass = (null !== $v = $data->StorageClass[0]) ? (string) $v : null;
        $this->checksumAlgorithm = (null !== $v = $data->ChecksumAlgorithm[0]) ? (string) $v : null;
    }

    private function populateResultInitiator(\SimpleXMLElement $xml): Initiator
    {
        return new Initiator([
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
            'DisplayName' => (null !== $v = $xml->DisplayName[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultOwner(\SimpleXMLElement $xml): Owner
    {
        return new Owner([
            'DisplayName' => (null !== $v = $xml->DisplayName[0]) ? (string) $v : null,
            'ID' => (null !== $v = $xml->ID[0]) ? (string) $v : null,
        ]);
    }

    private function populateResultPart(\SimpleXMLElement $xml): Part
    {
        return new Part([
            'PartNumber' => (null !== $v = $xml->PartNumber[0]) ? (int) (string) $v : null,
            'LastModified' => (null !== $v = $xml->LastModified[0]) ? new \DateTimeImmutable((string) $v) : null,
            'ETag' => (null !== $v = $xml->ETag[0]) ? (string) $v : null,
            'Size' => (null !== $v = $xml->Size[0]) ? (int) (string) $v : null,
            'ChecksumCRC32' => (null !== $v = $xml->ChecksumCRC32[0]) ? (string) $v : null,
            'ChecksumCRC32C' => (null !== $v = $xml->ChecksumCRC32C[0]) ? (string) $v : null,
            'ChecksumSHA1' => (null !== $v = $xml->ChecksumSHA1[0]) ? (string) $v : null,
            'ChecksumSHA256' => (null !== $v = $xml->ChecksumSHA256[0]) ? (string) $v : null,
        ]);
    }

    /**
     * @return Part[]
     */
    private function populateResultParts(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = $this->populateResultPart($item);
        }

        return $items;
    }
}
