<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class PutObjectRequest
{
    /**
     * @var string|null
     */
    private $ACL;

    /**
     * @var string|null
     */
    private $Body;

    /**
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * @var string|null
     */
    private $CacheControl;

    /**
     * @var string|null
     */
    private $ContentDisposition;

    /**
     * @var string|null
     */
    private $ContentEncoding;

    /**
     * @var string|null
     */
    private $ContentLanguage;

    /**
     * @var string|null
     */
    private $ContentLength;

    /**
     * @var string|null
     */
    private $ContentMD5;

    /**
     * @var string|null
     */
    private $ContentType;

    /**
     * @var \DateTimeImmutable|null
     */
    private $Expires;

    /**
     * @var string|null
     */
    private $GrantFullControl;

    /**
     * @var string|null
     */
    private $GrantRead;

    /**
     * @var string|null
     */
    private $GrantReadACP;

    /**
     * @var string|null
     */
    private $GrantWriteACP;

    /**
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * @var array|null
     */
    private $Metadata;

    /**
     * @var string|null
     */
    private $ServerSideEncryption;

    /**
     * @var string|null
     */
    private $StorageClass;

    /**
     * @var string|null
     */
    private $WebsiteRedirectLocation;

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
    private $SSEKMSKeyId;

    /**
     * @var string|null
     */
    private $SSEKMSEncryptionContext;

    /**
     * @var string|null
     */
    private $RequestPayer;

    /**
     * @var string|null
     */
    private $Tagging;

    /**
     * @var string|null
     */
    private $ObjectLockMode;

    /**
     * @var \DateTimeImmutable|null
     */
    private $ObjectLockRetainUntilDate;

    /**
     * @var string|null
     */
    private $ObjectLockLegalHoldStatus;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     *
     * @param array{
     *   ACL?: string,
     *   Body?: string,
     *   Bucket: string,
     *   CacheControl?: string,
     *   ContentDisposition?: string,
     *   ContentEncoding?: string,
     *   ContentLanguage?: string,
     *   ContentLength?: string,
     *   ContentMD5?: string,
     *   ContentType?: string,
     *   Expires?: \DateTimeImmutable|string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWriteACP?: string,
     *   Key: string,
     *   Metadata?: array,
     *   ServerSideEncryption?: string,
     *   StorageClass?: string,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   RequestPayer?: string,
     *   Tagging?: string,
     *   ObjectLockMode?: string,
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ACL = $input['ACL'] ?? null;
        $this->Body = $input['Body'] ?? null;
        $this->Bucket = $input['Bucket'] ?? null;
        $this->CacheControl = $input['CacheControl'] ?? null;
        $this->ContentDisposition = $input['ContentDisposition'] ?? null;
        $this->ContentEncoding = $input['ContentEncoding'] ?? null;
        $this->ContentLanguage = $input['ContentLanguage'] ?? null;
        $this->ContentLength = $input['ContentLength'] ?? null;
        $this->ContentMD5 = $input['ContentMD5'] ?? null;
        $this->ContentType = $input['ContentType'] ?? null;
        $this->Expires = !isset($input['Expires']) ? null : ($input['Expires'] instanceof \DateTimeImmutable ? $input['Expires'] : new \DateTimeImmutable($input['Expires']));
        $this->GrantFullControl = $input['GrantFullControl'] ?? null;
        $this->GrantRead = $input['GrantRead'] ?? null;
        $this->GrantReadACP = $input['GrantReadACP'] ?? null;
        $this->GrantWriteACP = $input['GrantWriteACP'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->Metadata = $input['Metadata'] ?? null;
        $this->ServerSideEncryption = $input['ServerSideEncryption'] ?? null;
        $this->StorageClass = $input['StorageClass'] ?? null;
        $this->WebsiteRedirectLocation = $input['WebsiteRedirectLocation'] ?? null;
        $this->SSECustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->SSECustomerKey = $input['SSECustomerKey'] ?? null;
        $this->SSECustomerKeyMD5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->SSEKMSKeyId = $input['SSEKMSKeyId'] ?? null;
        $this->SSEKMSEncryptionContext = $input['SSEKMSEncryptionContext'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->Tagging = $input['Tagging'] ?? null;
        $this->ObjectLockMode = $input['ObjectLockMode'] ?? null;
        $this->ObjectLockRetainUntilDate = !isset($input['ObjectLockRetainUntilDate']) ? null : ($input['ObjectLockRetainUntilDate'] instanceof \DateTimeImmutable ? $input['ObjectLockRetainUntilDate'] : new \DateTimeImmutable($input['ObjectLockRetainUntilDate']));
        $this->ObjectLockLegalHoldStatus = $input['ObjectLockLegalHoldStatus'] ?? null;
    }

    public function getACL(): ?string
    {
        return $this->ACL;
    }

    public function setACL(?string $value): self
    {
        $this->ACL = $value;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->Body;
    }

    public function setBody(?string $value): self
    {
        $this->Body = $value;

        return $this;
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

    public function getCacheControl(): ?string
    {
        return $this->CacheControl;
    }

    public function setCacheControl(?string $value): self
    {
        $this->CacheControl = $value;

        return $this;
    }

    public function getContentDisposition(): ?string
    {
        return $this->ContentDisposition;
    }

    public function setContentDisposition(?string $value): self
    {
        $this->ContentDisposition = $value;

        return $this;
    }

    public function getContentEncoding(): ?string
    {
        return $this->ContentEncoding;
    }

    public function setContentEncoding(?string $value): self
    {
        $this->ContentEncoding = $value;

        return $this;
    }

    public function getContentLanguage(): ?string
    {
        return $this->ContentLanguage;
    }

    public function setContentLanguage(?string $value): self
    {
        $this->ContentLanguage = $value;

        return $this;
    }

    public function getContentLength(): ?string
    {
        return $this->ContentLength;
    }

    public function setContentLength(?string $value): self
    {
        $this->ContentLength = $value;

        return $this;
    }

    public function getContentMD5(): ?string
    {
        return $this->ContentMD5;
    }

    public function setContentMD5(?string $value): self
    {
        $this->ContentMD5 = $value;

        return $this;
    }

    public function getContentType(): ?string
    {
        return $this->ContentType;
    }

    public function setContentType(?string $value): self
    {
        $this->ContentType = $value;

        return $this;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->Expires;
    }

    public function setExpires(?\DateTimeImmutable $value): self
    {
        $this->Expires = $value;

        return $this;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->GrantFullControl;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->GrantFullControl = $value;

        return $this;
    }

    public function getGrantRead(): ?string
    {
        return $this->GrantRead;
    }

    public function setGrantRead(?string $value): self
    {
        $this->GrantRead = $value;

        return $this;
    }

    public function getGrantReadACP(): ?string
    {
        return $this->GrantReadACP;
    }

    public function setGrantReadACP(?string $value): self
    {
        $this->GrantReadACP = $value;

        return $this;
    }

    public function getGrantWriteACP(): ?string
    {
        return $this->GrantWriteACP;
    }

    public function setGrantWriteACP(?string $value): self
    {
        $this->GrantWriteACP = $value;

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

    public function getMetadata(): ?array
    {
        return $this->Metadata;
    }

    public function setMetadata(?array $value): self
    {
        $this->Metadata = $value;

        return $this;
    }

    public function getServerSideEncryption(): ?string
    {
        return $this->ServerSideEncryption;
    }

    public function setServerSideEncryption(?string $value): self
    {
        $this->ServerSideEncryption = $value;

        return $this;
    }

    public function getStorageClass(): ?string
    {
        return $this->StorageClass;
    }

    public function setStorageClass(?string $value): self
    {
        $this->StorageClass = $value;

        return $this;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        return $this->WebsiteRedirectLocation;
    }

    public function setWebsiteRedirectLocation(?string $value): self
    {
        $this->WebsiteRedirectLocation = $value;

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

    public function getSSEKMSKeyId(): ?string
    {
        return $this->SSEKMSKeyId;
    }

    public function setSSEKMSKeyId(?string $value): self
    {
        $this->SSEKMSKeyId = $value;

        return $this;
    }

    public function getSSEKMSEncryptionContext(): ?string
    {
        return $this->SSEKMSEncryptionContext;
    }

    public function setSSEKMSEncryptionContext(?string $value): self
    {
        $this->SSEKMSEncryptionContext = $value;

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

    public function getTagging(): ?string
    {
        return $this->Tagging;
    }

    public function setTagging(?string $value): self
    {
        $this->Tagging = $value;

        return $this;
    }

    public function getObjectLockMode(): ?string
    {
        return $this->ObjectLockMode;
    }

    public function setObjectLockMode(?string $value): self
    {
        $this->ObjectLockMode = $value;

        return $this;
    }

    public function getObjectLockRetainUntilDate(): ?\DateTimeImmutable
    {
        return $this->ObjectLockRetainUntilDate;
    }

    public function setObjectLockRetainUntilDate(?\DateTimeImmutable $value): self
    {
        $this->ObjectLockRetainUntilDate = $value;

        return $this;
    }

    public function getObjectLockLegalHoldStatus(): ?string
    {
        return $this->ObjectLockLegalHoldStatus;
    }

    public function setObjectLockLegalHoldStatus(?string $value): self
    {
        $this->ObjectLockLegalHoldStatus = $value;

        return $this;
    }

    public function requestHeaders(): array
    {
        $headers = [];
        if (null !== $this->ACL) {
            $headers['x-amz-acl'] = $this->ACL;
        }
        if (null !== $this->CacheControl) {
            $headers['Cache-Control'] = $this->CacheControl;
        }
        if (null !== $this->ContentDisposition) {
            $headers['Content-Disposition'] = $this->ContentDisposition;
        }
        if (null !== $this->ContentEncoding) {
            $headers['Content-Encoding'] = $this->ContentEncoding;
        }
        if (null !== $this->ContentLanguage) {
            $headers['Content-Language'] = $this->ContentLanguage;
        }
        if (null !== $this->ContentLength) {
            $headers['Content-Length'] = $this->ContentLength;
        }
        if (null !== $this->ContentMD5) {
            $headers['Content-MD5'] = $this->ContentMD5;
        }
        if (null !== $this->ContentType) {
            $headers['Content-Type'] = $this->ContentType;
        }
        if (null !== $this->Expires) {
            $headers['Expires'] = $this->Expires;
        }
        if (null !== $this->GrantFullControl) {
            $headers['x-amz-grant-full-control'] = $this->GrantFullControl;
        }
        if (null !== $this->GrantRead) {
            $headers['x-amz-grant-read'] = $this->GrantRead;
        }
        if (null !== $this->GrantReadACP) {
            $headers['x-amz-grant-read-acp'] = $this->GrantReadACP;
        }
        if (null !== $this->GrantWriteACP) {
            $headers['x-amz-grant-write-acp'] = $this->GrantWriteACP;
        }
        if (null !== $this->ServerSideEncryption) {
            $headers['x-amz-server-side-encryption'] = $this->ServerSideEncryption;
        }
        if (null !== $this->StorageClass) {
            $headers['x-amz-storage-class'] = $this->StorageClass;
        }
        if (null !== $this->WebsiteRedirectLocation) {
            $headers['x-amz-website-redirect-location'] = $this->WebsiteRedirectLocation;
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
        if (null !== $this->SSEKMSKeyId) {
            $headers['x-amz-server-side-encryption-aws-kms-key-id'] = $this->SSEKMSKeyId;
        }
        if (null !== $this->SSEKMSEncryptionContext) {
            $headers['x-amz-server-side-encryption-context'] = $this->SSEKMSEncryptionContext;
        }
        if (null !== $this->RequestPayer) {
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }
        if (null !== $this->Tagging) {
            $headers['x-amz-tagging'] = $this->Tagging;
        }
        if (null !== $this->ObjectLockMode) {
            $headers['x-amz-object-lock-mode'] = $this->ObjectLockMode;
        }
        if (null !== $this->ObjectLockRetainUntilDate) {
            $headers['x-amz-object-lock-retain-until-date'] = $this->ObjectLockRetainUntilDate;
        }
        if (null !== $this->ObjectLockLegalHoldStatus) {
            $headers['x-amz-object-lock-legal-hold'] = $this->ObjectLockLegalHoldStatus;
        }

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'PutObject', 'Version' => '2006-03-01'];
        if (null !== $this->Body) {
            $payload['Body'] = $this->Body;
        }

        return $payload;
    }

    public function requestUri(): string
    {
        $uri = [];
        $uri['Bucket'] = $this->Bucket ?? '';
        $uri['Key'] = $this->Key ?? '';

        return "/{$uri['Bucket']}/{$uri['Key']}";
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
