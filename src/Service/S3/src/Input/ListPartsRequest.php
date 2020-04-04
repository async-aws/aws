<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\RequestPayer;

final class ListPartsRequest extends Input
{
    /**
     * Name of the bucket to which the parts are being uploaded.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Object key for which the multipart upload was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * Sets the maximum number of parts to return.
     *
     * @var int|null
     */
    private $MaxParts;

    /**
     * Specifies the part after which listing should begin. Only parts with higher part numbers will be listed.
     *
     * @var int|null
     */
    private $PartNumberMarker;

    /**
     * Upload ID identifying the multipart upload whose parts are being listed.
     *
     * @required
     *
     * @var string|null
     */
    private $UploadId;

    /**
     * @var null|RequestPayer::*
     */
    private $RequestPayer;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/mpUploadListParts.html
     *
     * @param array{
     *   Bucket?: string,
     *   Key?: string,
     *   MaxParts?: int,
     *   PartNumberMarker?: int,
     *   UploadId?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->MaxParts = $input['MaxParts'] ?? null;
        $this->PartNumberMarker = $input['PartNumberMarker'] ?? null;
        $this->UploadId = $input['UploadId'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getMaxParts(): ?int
    {
        return $this->MaxParts;
    }

    public function getPartNumberMarker(): ?int
    {
        return $this->PartNumberMarker;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getUploadId(): ?string
    {
        return $this->UploadId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->RequestPayer) {
            if (!RequestPayer::exists($this->RequestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->RequestPayer));
            }
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }

        // Prepare query
        $query = [];
        if (null !== $this->MaxParts) {
            $query['max-parts'] = (string) $this->MaxParts;
        }
        if (null !== $this->PartNumberMarker) {
            $query['part-number-marker'] = (string) $this->PartNumberMarker;
        }
        if (null === $v = $this->UploadId) {
            throw new InvalidArgument(sprintf('Missing parameter "UploadId" for "%s". The value cannot be null.', __CLASS__));
        }
        $query['uploadId'] = $v;

        // Prepare URI
        $uri = [];
        if (null === $v = $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        if (null === $v = $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Key'] = $v;
        $uriString = "/{$uri['Bucket']}/{$uri['Key']}";

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    public function setMaxParts(?int $value): self
    {
        $this->MaxParts = $value;

        return $this;
    }

    public function setPartNumberMarker(?int $value): self
    {
        $this->PartNumberMarker = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function setUploadId(?string $value): self
    {
        $this->UploadId = $value;

        return $this;
    }
}
