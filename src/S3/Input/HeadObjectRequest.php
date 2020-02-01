<?php

namespace AsyncAws\S3\Input;

class HeadObjectRequest
{
    /**
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * @var string|null
     */
    private $IfMatch;

    /**
     * @var int|null
     */
    private $IfModifiedSince;

    /**
     * @var string|null
     */
    private $IfNoneMatch;

    /**
     * @var int|null
     */
    private $IfUnmodifiedSince;

    /**
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * @var string|null
     */
    private $Range;

    /**
     * @var string|null
     */
    private $VersionId;

    /**
     * @var string|null
     */
    private $SSECustomerAlgorithm;

    /**
     * @var string|null
     */
    private $SSECustomerKey;

    /**
     * @var string|null
     */
    private $SSECustomerKeyMD5;

    /**
     * @var string|null
     */
    private $RequestPayer;

    /**
     * @var int|null
     */
    private $PartNumber;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectHEAD.html
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: string,
     *   IfModifiedSince?: int,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: int,
     *   Key: string,
     *   Range?: string,
     *   VersionId?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   RequestPayer?: string,
     *   PartNumber?: int,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Bucket = $input["Bucket"] ?? null;
        $this->IfMatch = $input["IfMatch"] ?? null;
        $this->IfModifiedSince = $input["IfModifiedSince"] ?? null;
        $this->IfNoneMatch = $input["IfNoneMatch"] ?? null;
        $this->IfUnmodifiedSince = $input["IfUnmodifiedSince"] ?? null;
        $this->Key = $input["Key"] ?? null;
        $this->Range = $input["Range"] ?? null;
        $this->VersionId = $input["VersionId"] ?? null;
        $this->SSECustomerAlgorithm = $input["SSECustomerAlgorithm"] ?? null;
        $this->SSECustomerKey = $input["SSECustomerKey"] ?? null;
        $this->SSECustomerKeyMD5 = $input["SSECustomerKeyMD5"] ?? null;
        $this->RequestPayer = $input["RequestPayer"] ?? null;
        $this->PartNumber = $input["PartNumber"] ?? null;
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function getIfMatch(): ?string
    {
        return $this->IfMatch;
    }

    public function setIfMatch(?string $value): self
    {
        $this->IfMatch = $value;

        return $this;
    }

    public function getIfModifiedSince(): ?int
    {
        return $this->IfModifiedSince;
    }

    public function setIfModifiedSince(?int $value): self
    {
        $this->IfModifiedSince = $value;

        return $this;
    }

    public function getIfNoneMatch(): ?string
    {
        return $this->IfNoneMatch;
    }

    public function setIfNoneMatch(?string $value): self
    {
        $this->IfNoneMatch = $value;

        return $this;
    }

    public function getIfUnmodifiedSince(): ?int
    {
        return $this->IfUnmodifiedSince;
    }

    public function setIfUnmodifiedSince(?int $value): self
    {
        $this->IfUnmodifiedSince = $value;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    public function getRange(): ?string
    {
        return $this->Range;
    }

    public function setRange(?string $value): self
    {
        $this->Range = $value;

        return $this;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    public function setVersionId(?string $value): self
    {
        $this->VersionId = $value;

        return $this;
    }

    public function getSSECustomerAlgorithm(): ?string
    {
        return $this->SSECustomerAlgorithm;
    }

    public function setSSECustomerAlgorithm(?string $value): self
    {
        $this->SSECustomerAlgorithm = $value;

        return $this;
    }

    public function getSSECustomerKey(): ?string
    {
        return $this->SSECustomerKey;
    }

    public function setSSECustomerKey(?string $value): self
    {
        $this->SSECustomerKey = $value;

        return $this;
    }

    public function getSSECustomerKeyMD5(): ?string
    {
        return $this->SSECustomerKeyMD5;
    }

    public function setSSECustomerKeyMD5(?string $value): self
    {
        $this->SSECustomerKeyMD5 = $value;

        return $this;
    }

    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function getPartNumber(): ?int
    {
        return $this->PartNumber;
    }

    public function setPartNumber(?int $value): self
    {
        $this->PartNumber = $value;

        return $this;
    }

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->IfMatch) {
            $headers["If-Match"] = $this->IfMatch;
        }
        if (null !== $this->IfModifiedSince) {
            $headers["If-Modified-Since"] = $this->IfModifiedSince;
        }
        if (null !== $this->IfNoneMatch) {
            $headers["If-None-Match"] = $this->IfNoneMatch;
        }
        if (null !== $this->IfUnmodifiedSince) {
            $headers["If-Unmodified-Since"] = $this->IfUnmodifiedSince;
        }
        if (null !== $this->Range) {
            $headers["Range"] = $this->Range;
        }
        if (null !== $this->SSECustomerAlgorithm) {
            $headers["x-amz-server-side-encryption-customer-algorithm"] = $this->SSECustomerAlgorithm;
        }
        if (null !== $this->SSECustomerKey) {
            $headers["x-amz-server-side-encryption-customer-key"] = $this->SSECustomerKey;
        }
        if (null !== $this->SSECustomerKeyMD5) {
            $headers["x-amz-server-side-encryption-customer-key-MD5"] = $this->SSECustomerKeyMD5;
        }
        if (null !== $this->RequestPayer) {
            $headers["x-amz-request-payer"] = $this->RequestPayer;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->VersionId) {
            $query["versionId"] = $this->VersionId;
        }
        if (null !== $this->PartNumber) {
            $query["partNumber"] = $this->PartNumber;
        }

        return $query;
    }

    public function requestBody(): array
    {
        $payload = [];

        return $payload;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';
        $uri['Key'] = $this->Key ?? '';

        return "/{$uri['Bucket']}/{$uri['Key']}";
    }
}
