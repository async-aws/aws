<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\ObjectCannedACL;
use AsyncAws\S3\Enum\ObjectLockLegalHoldStatus;
use AsyncAws\S3\Enum\ObjectLockMode;
use AsyncAws\S3\Enum\RequestPayer;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Enum\StorageClass;

final class CreateMultipartUploadRequest extends Input
{
    /**
     * The canned ACL to apply to the object.
     *
     * @var null|ObjectCannedACL::*
     */
    private $ACL;

    /**
     * The name of the bucket to which to initiate the upload.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Specifies caching behavior along the request/reply chain.
     *
     * @var string|null
     */
    private $CacheControl;

    /**
     * Specifies presentational information for the object.
     *
     * @var string|null
     */
    private $ContentDisposition;

    /**
     * Specifies what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field.
     *
     * @var string|null
     */
    private $ContentEncoding;

    /**
     * The language the content is in.
     *
     * @var string|null
     */
    private $ContentLanguage;

    /**
     * A standard MIME type describing the format of the object data.
     *
     * @var string|null
     */
    private $ContentType;

    /**
     * The date and time at which the object is no longer cacheable.
     *
     * @var \DateTimeImmutable|null
     */
    private $Expires;

    /**
     * Gives the grantee READ, READ_ACP, and WRITE_ACP permissions on the object.
     *
     * @var string|null
     */
    private $GrantFullControl;

    /**
     * Allows grantee to read the object data and its metadata.
     *
     * @var string|null
     */
    private $GrantRead;

    /**
     * Allows grantee to read the object ACL.
     *
     * @var string|null
     */
    private $GrantReadACP;

    /**
     * Allows grantee to write the ACL for the applicable object.
     *
     * @var string|null
     */
    private $GrantWriteACP;

    /**
     * Object key for which the multipart upload is to be initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * A map of metadata to store with the object in S3.
     *
     * @var array<string, string>|null
     */
    private $Metadata;

    /**
     * The server-side encryption algorithm used when storing this object in Amazon S3 (for example, AES256, aws:kms).
     *
     * @var null|ServerSideEncryption::*
     */
    private $ServerSideEncryption;

    /**
     * The type of storage to use for the object. Defaults to 'STANDARD'.
     *
     * @var null|StorageClass::*
     */
    private $StorageClass;

    /**
     * If the bucket is configured as a website, redirects requests for this object to another object in the same bucket or
     * to an external URL. Amazon S3 stores the value of this header in the object metadata.
     *
     * @var string|null
     */
    private $WebsiteRedirectLocation;

    /**
     * Specifies the algorithm to use to when encrypting the object (for example, AES256).
     *
     * @var string|null
     */
    private $SSECustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use in encrypting data. This value is used to store
     * the object and then it is discarded; Amazon S3 does not store the encryption key. The key must be appropriate for use
     * with the algorithm specified in the `x-amz-server-side-encryption-customer-algorithm` header.
     *
     * @var string|null
     */
    private $SSECustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * @var string|null
     */
    private $SSECustomerKeyMD5;

    /**
     * Specifies the ID of the symmetric customer managed AWS KMS CMK to use for object encryption. All GET and PUT requests
     * for an object protected by AWS KMS will fail if not made via SSL or using SigV4. For information about configuring
     * using any of the officially supported AWS SDKs and AWS CLI, see Specifying the Signature Version in Request
     * Authentication in the *Amazon S3 Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/http:/docs.aws.amazon.com/AmazonS3/latest/dev/UsingAWSSDK.html#specify-signature-version
     *
     * @var string|null
     */
    private $SSEKMSKeyId;

    /**
     * Specifies the AWS KMS Encryption Context to use for object encryption. The value of this header is a base64-encoded
     * UTF-8 string holding JSON with the encryption context key-value pairs.
     *
     * @var string|null
     */
    private $SSEKMSEncryptionContext;

    /**
     * @var null|RequestPayer::*
     */
    private $RequestPayer;

    /**
     * The tag-set for the object. The tag-set must be encoded as URL Query parameters.
     *
     * @var string|null
     */
    private $Tagging;

    /**
     * Specifies the Object Lock mode that you want to apply to the uploaded object.
     *
     * @var null|ObjectLockMode::*
     */
    private $ObjectLockMode;

    /**
     * Specifies the date and time when you want the Object Lock to expire.
     *
     * @var \DateTimeImmutable|null
     */
    private $ObjectLockRetainUntilDate;

    /**
     * Specifies whether you want to apply a Legal Hold to the uploaded object.
     *
     * @var null|ObjectLockLegalHoldStatus::*
     */
    private $ObjectLockLegalHoldStatus;

    /**
     * The account id of the expected bucket owner. If the bucket is owned by a different account, the request will fail
     * with an HTTP `403 (Access Denied)` error.
     *
     * @var string|null
     */
    private $ExpectedBucketOwner;

    /**
     * @param array{
     *   ACL?: ObjectCannedACL::*,
     *   Bucket?: string,
     *   CacheControl?: string,
     *   ContentDisposition?: string,
     *   ContentEncoding?: string,
     *   ContentLanguage?: string,
     *   ContentType?: string,
     *   Expires?: \DateTimeImmutable|string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWriteACP?: string,
     *   Key?: string,
     *   Metadata?: array<string, string>,
     *   ServerSideEncryption?: ServerSideEncryption::*,
     *   StorageClass?: StorageClass::*,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   RequestPayer?: RequestPayer::*,
     *   Tagging?: string,
     *   ObjectLockMode?: ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: ObjectLockLegalHoldStatus::*,
     *   ExpectedBucketOwner?: string,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ACL = $input['ACL'] ?? null;
        $this->Bucket = $input['Bucket'] ?? null;
        $this->CacheControl = $input['CacheControl'] ?? null;
        $this->ContentDisposition = $input['ContentDisposition'] ?? null;
        $this->ContentEncoding = $input['ContentEncoding'] ?? null;
        $this->ContentLanguage = $input['ContentLanguage'] ?? null;
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
        $this->ExpectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ObjectCannedACL::*|null
     */
    public function getACL(): ?string
    {
        return $this->ACL;
    }

    public function getBucket(): ?string
    {
        return $this->Bucket;
    }

    public function getCacheControl(): ?string
    {
        return $this->CacheControl;
    }

    public function getContentDisposition(): ?string
    {
        return $this->ContentDisposition;
    }

    public function getContentEncoding(): ?string
    {
        return $this->ContentEncoding;
    }

    public function getContentLanguage(): ?string
    {
        return $this->ContentLanguage;
    }

    public function getContentType(): ?string
    {
        return $this->ContentType;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->ExpectedBucketOwner;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->Expires;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->GrantFullControl;
    }

    public function getGrantRead(): ?string
    {
        return $this->GrantRead;
    }

    public function getGrantReadACP(): ?string
    {
        return $this->GrantReadACP;
    }

    public function getGrantWriteACP(): ?string
    {
        return $this->GrantWriteACP;
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->Metadata ?? [];
    }

    /**
     * @return ObjectLockLegalHoldStatus::*|null
     */
    public function getObjectLockLegalHoldStatus(): ?string
    {
        return $this->ObjectLockLegalHoldStatus;
    }

    /**
     * @return ObjectLockMode::*|null
     */
    public function getObjectLockMode(): ?string
    {
        return $this->ObjectLockMode;
    }

    public function getObjectLockRetainUntilDate(): ?\DateTimeImmutable
    {
        return $this->ObjectLockRetainUntilDate;
    }

    /**
     * @return RequestPayer::*|null
     */
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

    public function getSSEKMSEncryptionContext(): ?string
    {
        return $this->SSEKMSEncryptionContext;
    }

    public function getSSEKMSKeyId(): ?string
    {
        return $this->SSEKMSKeyId;
    }

    /**
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        return $this->ServerSideEncryption;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->StorageClass;
    }

    public function getTagging(): ?string
    {
        return $this->Tagging;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        return $this->WebsiteRedirectLocation;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/xml'];
        if (null !== $this->ACL) {
            if (!ObjectCannedACL::exists($this->ACL)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ACL" for "%s". The value "%s" is not a valid "ObjectCannedACL".', __CLASS__, $this->ACL));
            }
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
        if (null !== $this->ContentType) {
            $headers['Content-Type'] = $this->ContentType;
        }
        if (null !== $this->Expires) {
            $headers['Expires'] = $this->Expires->format(\DateTimeInterface::RFC822);
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
            if (!ServerSideEncryption::exists($this->ServerSideEncryption)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ServerSideEncryption" for "%s". The value "%s" is not a valid "ServerSideEncryption".', __CLASS__, $this->ServerSideEncryption));
            }
            $headers['x-amz-server-side-encryption'] = $this->ServerSideEncryption;
        }
        if (null !== $this->StorageClass) {
            if (!StorageClass::exists($this->StorageClass)) {
                throw new InvalidArgument(sprintf('Invalid parameter "StorageClass" for "%s". The value "%s" is not a valid "StorageClass".', __CLASS__, $this->StorageClass));
            }
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
            if (!RequestPayer::exists($this->RequestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->RequestPayer));
            }
            $headers['x-amz-request-payer'] = $this->RequestPayer;
        }
        if (null !== $this->Tagging) {
            $headers['x-amz-tagging'] = $this->Tagging;
        }
        if (null !== $this->ObjectLockMode) {
            if (!ObjectLockMode::exists($this->ObjectLockMode)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ObjectLockMode" for "%s". The value "%s" is not a valid "ObjectLockMode".', __CLASS__, $this->ObjectLockMode));
            }
            $headers['x-amz-object-lock-mode'] = $this->ObjectLockMode;
        }
        if (null !== $this->ObjectLockRetainUntilDate) {
            $headers['x-amz-object-lock-retain-until-date'] = $this->ObjectLockRetainUntilDate->format(\DateTimeInterface::ISO8601);
        }
        if (null !== $this->ObjectLockLegalHoldStatus) {
            if (!ObjectLockLegalHoldStatus::exists($this->ObjectLockLegalHoldStatus)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ObjectLockLegalHoldStatus" for "%s". The value "%s" is not a valid "ObjectLockLegalHoldStatus".', __CLASS__, $this->ObjectLockLegalHoldStatus));
            }
            $headers['x-amz-object-lock-legal-hold'] = $this->ObjectLockLegalHoldStatus;
        }
        if (null !== $this->ExpectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->ExpectedBucketOwner;
        }
        if (null !== $this->Metadata) {
            foreach ($this->Metadata as $key => $value) {
                $headers["x-amz-meta-$key"] = $value;
            }
        }

        // Prepare query
        $query = [];

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
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key'])) . '?uploads';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param ObjectCannedACL::*|null $value
     */
    public function setACL(?string $value): self
    {
        $this->ACL = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->Bucket = $value;

        return $this;
    }

    public function setCacheControl(?string $value): self
    {
        $this->CacheControl = $value;

        return $this;
    }

    public function setContentDisposition(?string $value): self
    {
        $this->ContentDisposition = $value;

        return $this;
    }

    public function setContentEncoding(?string $value): self
    {
        $this->ContentEncoding = $value;

        return $this;
    }

    public function setContentLanguage(?string $value): self
    {
        $this->ContentLanguage = $value;

        return $this;
    }

    public function setContentType(?string $value): self
    {
        $this->ContentType = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->ExpectedBucketOwner = $value;

        return $this;
    }

    public function setExpires(?\DateTimeImmutable $value): self
    {
        $this->Expires = $value;

        return $this;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->GrantFullControl = $value;

        return $this;
    }

    public function setGrantRead(?string $value): self
    {
        $this->GrantRead = $value;

        return $this;
    }

    public function setGrantReadACP(?string $value): self
    {
        $this->GrantReadACP = $value;

        return $this;
    }

    public function setGrantWriteACP(?string $value): self
    {
        $this->GrantWriteACP = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setMetadata(array $value): self
    {
        $this->Metadata = $value;

        return $this;
    }

    /**
     * @param ObjectLockLegalHoldStatus::*|null $value
     */
    public function setObjectLockLegalHoldStatus(?string $value): self
    {
        $this->ObjectLockLegalHoldStatus = $value;

        return $this;
    }

    /**
     * @param ObjectLockMode::*|null $value
     */
    public function setObjectLockMode(?string $value): self
    {
        $this->ObjectLockMode = $value;

        return $this;
    }

    public function setObjectLockRetainUntilDate(?\DateTimeImmutable $value): self
    {
        $this->ObjectLockRetainUntilDate = $value;

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

    public function setSSEKMSEncryptionContext(?string $value): self
    {
        $this->SSEKMSEncryptionContext = $value;

        return $this;
    }

    public function setSSEKMSKeyId(?string $value): self
    {
        $this->SSEKMSKeyId = $value;

        return $this;
    }

    /**
     * @param ServerSideEncryption::*|null $value
     */
    public function setServerSideEncryption(?string $value): self
    {
        $this->ServerSideEncryption = $value;

        return $this;
    }

    /**
     * @param StorageClass::*|null $value
     */
    public function setStorageClass(?string $value): self
    {
        $this->StorageClass = $value;

        return $this;
    }

    public function setTagging(?string $value): self
    {
        $this->Tagging = $value;

        return $this;
    }

    public function setWebsiteRedirectLocation(?string $value): self
    {
        $this->WebsiteRedirectLocation = $value;

        return $this;
    }
}
