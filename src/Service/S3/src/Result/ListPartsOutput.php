<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
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
     * Multipart Uploads Using a Bucket Lifecycle Policy.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html#mpu-abort-incomplete-mpu-lifecycle-config
     */
    private $AbortDate = null;

    /**
     * This header is returned along with the `x-amz-abort-date` header. It identifies applicable lifecycle configuration
     * rule that defines the action to abort incomplete multipart uploads.
     */
    private $AbortRuleId = null;

    /**
     * The name of the bucket to which the multipart upload was initiated.
     */
    private $Bucket = null;

    /**
     * Object key for which the multipart upload was initiated.
     */
    private $Key = null;

    /**
     * Upload ID identifying the multipart upload whose parts are being listed.
     */
    private $UploadId = null;

    /**
     * When a list is truncated, this element specifies the last part in the list, as well as the value to use for the
     * part-number-marker request parameter in a subsequent request.
     */
    private $PartNumberMarker = null;

    /**
     * When a list is truncated, this element specifies the last part in the list, as well as the value to use for the
     * part-number-marker request parameter in a subsequent request.
     */
    private $NextPartNumberMarker = null;

    /**
     * Maximum number of parts that were allowed in the response.
     */
    private $MaxParts = null;

    /**
     * Indicates whether the returned list of parts is truncated. A true value indicates that the list was truncated. A list
     * can be truncated if the number of parts exceeds the limit returned in the MaxParts element.
     */
    private $IsTruncated = null;

    /**
     * Container for elements related to a particular part. A response can contain zero or more `Part` elements.
     */
    private $Parts = [];

    /**
     * Container element that identifies who initiated the multipart upload. If the initiator is an AWS account, this
     * element provides the same information as the `Owner` element. If the initiator is an IAM User, this element provides
     * the user ARN and display name.
     */
    private $Initiator = null;

    /**
     * Container element that identifies the object owner, after the object is created. If multipart upload is initiated by
     * an IAM user, this element provides the parent account ID and display name.
     */
    private $Owner = null;

    /**
     * Class of storage (STANDARD or REDUCED_REDUNDANCY) used to store the uploaded object.
     */
    private $StorageClass = null;

    private $RequestCharged = null;

    public function getAbortDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->AbortDate;
    }

    public function getAbortRuleId(): ?string
    {
        $this->initialize();

        return $this->AbortRuleId;
    }

    public function getBucket(): ?string
    {
        $this->initialize();

        return $this->Bucket;
    }

    public function getInitiator(): ?Initiator
    {
        $this->initialize();

        return $this->Initiator;
    }

    public function getIsTruncated(): ?bool
    {
        $this->initialize();

        return $this->IsTruncated;
    }

    /**
     * Iterates over Parts.
     *
     * @return \Traversable<Part>
     */
    public function getIterator(): \Traversable
    {
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
            if ($page->getIsTruncated()) {
                $input->setPartNumberMarker($page->getNextPartNumberMarker());

                $this->registerPrefetch($nextPage = $client->ListParts($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getParts(true);

            if (null === $nextPage) {
                break;
            }

            $this->unregisterPrefetch($nextPage);
            $page = $nextPage;
        }
    }

    public function getKey(): ?string
    {
        $this->initialize();

        return $this->Key;
    }

    public function getMaxParts(): ?int
    {
        $this->initialize();

        return $this->MaxParts;
    }

    public function getNextPartNumberMarker(): ?int
    {
        $this->initialize();

        return $this->NextPartNumberMarker;
    }

    public function getOwner(): ?Owner
    {
        $this->initialize();

        return $this->Owner;
    }

    public function getPartNumberMarker(): ?int
    {
        $this->initialize();

        return $this->PartNumberMarker;
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
            yield from $this->Parts;

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
            if ($page->getIsTruncated()) {
                $input->setPartNumberMarker($page->getNextPartNumberMarker());

                $this->registerPrefetch($nextPage = $client->ListParts($input));
            } else {
                $nextPage = null;
            }

            yield from $page->getParts(true);

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

        return $this->RequestCharged;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        $this->initialize();

        return $this->StorageClass;
    }

    public function getUploadId(): ?string
    {
        $this->initialize();

        return $this->UploadId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->AbortDate = isset($headers['x-amz-abort-date'][0]) ? new \DateTimeImmutable($headers['x-amz-abort-date'][0]) : null;
        $this->AbortRuleId = $headers['x-amz-abort-rule-id'][0] ?? null;
        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->Bucket = ($v = $data->Bucket) ? (string) $v : null;
        $this->Key = ($v = $data->Key) ? (string) $v : null;
        $this->UploadId = ($v = $data->UploadId) ? (string) $v : null;
        $this->PartNumberMarker = ($v = $data->PartNumberMarker) ? (int) (string) $v : null;
        $this->NextPartNumberMarker = ($v = $data->NextPartNumberMarker) ? (int) (string) $v : null;
        $this->MaxParts = ($v = $data->MaxParts) ? (int) (string) $v : null;
        $this->IsTruncated = ($v = $data->IsTruncated) ? filter_var((string) $v, \FILTER_VALIDATE_BOOLEAN) : null;
        $this->Parts = !$data->Part ? [] : $this->populateResultParts($data->Part);
        $this->Initiator = !$data->Initiator ? null : new Initiator([
            'ID' => ($v = $data->Initiator->ID) ? (string) $v : null,
            'DisplayName' => ($v = $data->Initiator->DisplayName) ? (string) $v : null,
        ]);
        $this->Owner = !$data->Owner ? null : new Owner([
            'DisplayName' => ($v = $data->Owner->DisplayName) ? (string) $v : null,
            'ID' => ($v = $data->Owner->ID) ? (string) $v : null,
        ]);
        $this->StorageClass = ($v = $data->StorageClass) ? (string) $v : null;
    }

    /**
     * @return Part[]
     */
    private function populateResultParts(\SimpleXMLElement $xml): array
    {
        $items = [];
        foreach ($xml as $item) {
            $items[] = new Part([
                'PartNumber' => ($v = $item->PartNumber) ? (int) (string) $v : null,
                'LastModified' => ($v = $item->LastModified) ? new \DateTimeImmutable((string) $v) : null,
                'ETag' => ($v = $item->ETag) ? (string) $v : null,
                'Size' => ($v = $item->Size) ? (string) $v : null,
            ]);
        }

        return $items;
    }
}
