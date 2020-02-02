<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

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
     * @var \DateTimeImmutable|null
     */
    private $IfModifiedSince;
    /**
     * @var string|null
     */
    private $IfNoneMatch;
    /**
     * @var \DateTimeImmutable|null
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

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectHEAD.html
     *
     * @param array{
     *   Bucket: string,
     *   IfMatch?: string,
     *   IfModifiedSince?: \DateTimeImmutable|string,
     *   IfNoneMatch?: string,
     *   IfUnmodifiedSince?: \DateTimeImmutable|string,
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
        $this->Bucket = $input['Bucket'] ?? null;
        $this->IfMatch = $input['IfMatch'] ?? null;
        $this->IfModifiedSince = !isset($input['IfModifiedSince']) ? null : ($input['IfModifiedSince'] instanceof \DateTimeImmutable ? $input['IfModifiedSince'] : new \DateTimeImmutable($input['IfModifiedSince']));
        $this->IfNoneMatch = $input['IfNoneMatch'] ?? null;
        $this->IfUnmodifiedSince = !isset($input['IfUnmodifiedSince']) ? null : ($input['IfUnmodifiedSince'] instanceof \DateTimeImmutable ? $input['IfUnmodifiedSince'] : new \DateTimeImmutable($input['IfUnmodifiedSince']));
        $this->Key = $input['Key'] ?? null;
        $this->Range = $input['Range'] ?? null;
        $this->VersionId = $input['VersionId'] ?? null;
        $this->SSECustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->SSECustomerKey = $input['SSECustomerKey'] ?? null;
        $this->SSECustomerKeyMD5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->PartNumber = $input['PartNumber'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getIfMatch(): ?string
    {
        return $this->IfMatch;
    }

    public function getIfModifiedSince(): ?\DateTimeImmutable
    {
        return $this->IfModifiedSince;
    }

    public function getIfNoneMatch(): ?string
    {
        return $this->IfNoneMatch;
    }

    public function getIfUnmodifiedSince(): ?\DateTimeImmutable
    {
        return $this->IfUnmodifiedSince;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getPartNumber(): ?int
    {
        return $this->PartNumber;
    }

    public function getRange(): ?string
    {
        return $this->Range;
    }

    public function getRequestPayer(): ?string
    {
        return $this->RequestPayer;
    }

    public function getSSECustomerAlgorithm(): ?string
    {
        return $this->SSECustomerAlgorithm;
    }

    public function getSSECustomerKey(): ?string
    {
        return $this->SSECustomerKey;
    }

    public function getSSECustomerKeyMD5(): ?string
    {
        return $this->SSECustomerKeyMD5;
    }

    public function getVersionId(): ?string
    {
        return $this->VersionId;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'HeadObject', 'Version' => '2006-03-01'];

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->IfMatch) {
            $headers['If-Match'] = $this->IfMatch;
        }
        if (null !== $this->IfModifiedSince) {
            $headers['If-Modified-Since'] = $this->IfModifiedSince;
        }
        if (null !== $this->IfNoneMatch) {
            $headers['If-None-Match'] = $this->IfNoneMatch;
        }
        if (null !== $this->IfUnmodifiedSince) {
            $headers['If-Unmodified-Since'] = $this->IfUnmodifiedSince;
        }
        if (null !== $this->Range) {
            $headers['Range'] = $this->Range;
        }
        if (null !== $this->SSECustomerAlgorithm) {
            $headers['x-amz-server-side-encryption-customer-algorithm'] = $this->SSECustomerAlgorithm;
        }
        if (null !== $this->SSECustomerKey) {
            $headers['x-amz-server-side-encryption-customer-key'] = $this->SSECustomerKey;
        }
        if (null !== $this->SSECustomerKeyMD5) {
            $headers['x-amz-server-side-encryption-customer-key-MD5'] = $this->SSECustomerKeyMD5;
        }
        if (null !== $this->RequestPayer) {
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];
        if (null !== $this->VersionId) {
            $query['versionId'] = $this->VersionId;
        }
        if (null !== $this->PartNumber) {
            $query['partNumber'] = $this->PartNumber;
        }

        return $query;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';
        $uri['Key'] = $this->Key ?? '';

        return "/{$uri['Bucket']}/{$uri['Key']}";
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setIfMatch(?string $value): self
    {
        $this->IfMatch = $value;

        return $this;
    }

    public function setIfModifiedSince(?\DateTimeImmutable $value): self
    {
        $this->IfModifiedSince = $value;

        return $this;
    }

    public function setIfNoneMatch(?string $value): self
    {
        $this->IfNoneMatch = $value;

        return $this;
    }

    public function setIfUnmodifiedSince(?\DateTimeImmutable $value): self
    {
        $this->IfUnmodifiedSince = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    public function setPartNumber(?int $value): self
    {
        $this->PartNumber = $value;

        return $this;
    }

    public function setRange(?string $value): self
    {
        $this->Range = $value;

        return $this;
    }

    public function setRequestPayer(?string $value): self
    {
        $this->RequestPayer = $value;

        return $this;
    }

    public function setSSECustomerAlgorithm(?string $value): self
    {
        $this->SSECustomerAlgorithm = $value;

        return $this;
    }

    public function setSSECustomerKey(?string $value): self
    {
        $this->SSECustomerKey = $value;

        return $this;
    }

    public function setSSECustomerKeyMD5(?string $value): self
    {
        $this->SSECustomerKeyMD5 = $value;

        return $this;
    }

    public function setVersionId(?string $value): self
    {
        $this->VersionId = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['Bucket', 'Key'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
