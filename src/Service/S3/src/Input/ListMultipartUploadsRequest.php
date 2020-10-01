<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\EncodingType;

final class ListMultipartUploadsRequest extends Input
{
    /**
     * The name of the bucket to which the multipart upload was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Character you use to group keys.
     *
     * @var string|null
     */
    private $Delimiter;

    /**
     * @var null|EncodingType::*
     */
    private $EncodingType;

    /**
     * Together with upload-id-marker, this parameter specifies the multipart upload after which listing should begin.
     *
     * @var string|null
     */
    private $KeyMarker;

    /**
     * Sets the maximum number of multipart uploads, from 1 to 1,000, to return in the response body. 1,000 is the maximum
     * number of uploads that can be returned in a response.
     *
     * @var int|null
     */
    private $MaxUploads;

    /**
     * Lists in-progress uploads only for those keys that begin with the specified prefix. You can use prefixes to separate
     * a bucket into different grouping of keys. (You can think of using prefix to make groups in the same way you'd use a
     * folder in a file system.).
     *
     * @var string|null
     */
    private $Prefix;

    /**
     * Together with key-marker, specifies the multipart upload after which listing should begin. If key-marker is not
     * specified, the upload-id-marker parameter is ignored. Otherwise, any multipart uploads for a key equal to the
     * key-marker might be included in the list only if they have an upload ID lexicographically greater than the specified
     * `upload-id-marker`.
     *
     * @var string|null
     */
    private $UploadIdMarker;

    /**
     * The account id of the expected bucket owner. If the bucket is owned by a different account, the request will fail
     * with an HTTP `403 (Access Denied)` error.
     *
     * @var string|null
     */
    private $ExpectedBucketOwner;

    /**
     * @param array{
     *   Bucket?: string,
     *   Delimiter?: string,
     *   EncodingType?: EncodingType::*,
     *   KeyMarker?: string,
     *   MaxUploads?: int,
     *   Prefix?: string,
     *   UploadIdMarker?: string,
     *   ExpectedBucketOwner?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input['Bucket'] ?? null;
        $this->Delimiter = $input['Delimiter'] ?? null;
        $this->EncodingType = $input['EncodingType'] ?? null;
        $this->KeyMarker = $input['KeyMarker'] ?? null;
        $this->MaxUploads = $input['MaxUploads'] ?? null;
        $this->Prefix = $input['Prefix'] ?? null;
        $this->UploadIdMarker = $input['UploadIdMarker'] ?? null;
        $this->ExpectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
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

    public function getDelimiter(): ?string
    {
        return $this->Delimiter;
    }

    /**
     * @return EncodingType::*|null
     */
    public function getEncodingType(): ?string
    {
        return $this->EncodingType;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->ExpectedBucketOwner;
    }

    public function getKeyMarker(): ?string
    {
        return $this->KeyMarker;
    }

    public function getMaxUploads(): ?int
    {
        return $this->MaxUploads;
    }

    public function getPrefix(): ?string
    {
        return $this->Prefix;
    }

    public function getUploadIdMarker(): ?string
    {
        return $this->UploadIdMarker;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->ExpectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->ExpectedBucketOwner;
        }

        // Prepare query
        $query = [];
        if (null !== $this->Delimiter) {
            $query['delimiter'] = $this->Delimiter;
        }
        if (null !== $this->EncodingType) {
            if (!EncodingType::exists($this->EncodingType)) {
                throw new InvalidArgument(sprintf('Invalid parameter "EncodingType" for "%s". The value "%s" is not a valid "EncodingType".', __CLASS__, $this->EncodingType));
            }
            $query['encoding-type'] = $this->EncodingType;
        }
        if (null !== $this->KeyMarker) {
            $query['key-marker'] = $this->KeyMarker;
        }
        if (null !== $this->MaxUploads) {
            $query['max-uploads'] = (string) $this->MaxUploads;
        }
        if (null !== $this->Prefix) {
            $query['prefix'] = $this->Prefix;
        }
        if (null !== $this->UploadIdMarker) {
            $query['upload-id-marker'] = $this->UploadIdMarker;
        }

        // Prepare URI
        $uri = [];
        if (null === $v = $this->Bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '?uploads';

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

    public function setDelimiter(?string $value): self
    {
        $this->Delimiter = $value;

        return $this;
    }

    /**
     * @param EncodingType::*|null $value
     */
    public function setEncodingType(?string $value): self
    {
        $this->EncodingType = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->ExpectedBucketOwner = $value;

        return $this;
    }

    public function setKeyMarker(?string $value): self
    {
        $this->KeyMarker = $value;

        return $this;
    }

    public function setMaxUploads(?int $value): self
    {
        $this->MaxUploads = $value;

        return $this;
    }

    public function setPrefix(?string $value): self
    {
        $this->Prefix = $value;

        return $this;
    }

    public function setUploadIdMarker(?string $value): self
    {
        $this->UploadIdMarker = $value;

        return $this;
    }
}
