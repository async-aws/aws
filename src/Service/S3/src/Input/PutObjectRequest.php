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

final class PutObjectRequest extends Input
{
    /**
     * The canned ACL to apply to the object. For more information, see Canned ACL.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/acl-overview.html#CannedACL
     *
     * @var null|ObjectCannedACL::*
     */
    private $aCL;

    /**
     * Object data.
     *
     * @var string|resource|callable|iterable|null
     */
    private $body;

    /**
     * The bucket name to which the PUT operation was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $bucket;

    /**
     * Can be used to specify caching behavior along the request/reply chain. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9
     *
     * @var string|null
     */
    private $cacheControl;

    /**
     * Specifies presentational information for the object. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.5.1.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.5.1
     *
     * @var string|null
     */
    private $contentDisposition;

    /**
     * Specifies what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.11.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.11
     *
     * @var string|null
     */
    private $contentEncoding;

    /**
     * The language the content is in.
     *
     * @var string|null
     */
    private $contentLanguage;

    /**
     * Size of the body in bytes. This parameter is useful when the size of the body cannot be determined automatically. For
     * more information, see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13
     *
     * @var string|null
     */
    private $contentLength;

    /**
     * The base64-encoded 128-bit MD5 digest of the message (without the headers) according to RFC 1864. This header can be
     * used as a message integrity check to verify that the data is the same data that was originally sent. Although it is
     * optional, we recommend using the Content-MD5 mechanism as an end-to-end integrity check. For more information about
     * REST request authentication, see REST Authentication.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/RESTAuthentication.html
     *
     * @var string|null
     */
    private $contentMD5;

    /**
     * A standard MIME type describing the format of the contents. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     *
     * @var string|null
     */
    private $contentType;

    /**
     * The date and time at which the object is no longer cacheable. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21
     *
     * @var \DateTimeImmutable|null
     */
    private $expires;

    /**
     * Gives the grantee READ, READ_ACP, and WRITE_ACP permissions on the object.
     *
     * @var string|null
     */
    private $grantFullControl;

    /**
     * Allows grantee to read the object data and its metadata.
     *
     * @var string|null
     */
    private $grantRead;

    /**
     * Allows grantee to read the object ACL.
     *
     * @var string|null
     */
    private $grantReadACP;

    /**
     * Allows grantee to write the ACL for the applicable object.
     *
     * @var string|null
     */
    private $grantWriteACP;

    /**
     * Object key for which the PUT operation was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $key;

    /**
     * A map of metadata to store with the object in S3.
     *
     * @var array<string, string>|null
     */
    private $metadata;

    /**
     * The server-side encryption algorithm used when storing this object in Amazon S3 (for example, AES256, aws:kms).
     *
     * @var null|ServerSideEncryption::*
     */
    private $serverSideEncryption;

    /**
     * By default, Amazon S3 uses the STANDARD Storage Class to store newly created objects. The STANDARD storage class
     * provides high durability and high availability. Depending on performance needs, you can specify a different Storage
     * Class. Amazon S3 on Outposts only uses the OUTPOSTS Storage Class. For more information, see Storage Classes in the
     * *Amazon S3 Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/storage-class-intro.html
     *
     * @var null|StorageClass::*
     */
    private $storageClass;

    /**
     * If the bucket is configured as a website, redirects requests for this object to another object in the same bucket or
     * to an external URL. Amazon S3 stores the value of this header in the object metadata. For information about object
     * metadata, see Object Key and Metadata.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingMetadata.html
     *
     * @var string|null
     */
    private $websiteRedirectLocation;

    /**
     * Specifies the algorithm to use to when encrypting the object (for example, AES256).
     *
     * @var string|null
     */
    private $sSECustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use in encrypting data. This value is used to store
     * the object and then it is discarded; Amazon S3 does not store the encryption key. The key must be appropriate for use
     * with the algorithm specified in the `x-amz-server-side-encryption-customer-algorithm` header.
     *
     * @var string|null
     */
    private $sSECustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * @var string|null
     */
    private $sSECustomerKeyMD5;

    /**
     * If `x-amz-server-side-encryption` is present and has the value of `aws:kms`, this header specifies the ID of the AWS
     * Key Management Service (AWS KMS) symmetrical customer managed customer master key (CMK) that was used for the object.
     *
     * @var string|null
     */
    private $sSEKMSKeyId;

    /**
     * Specifies the AWS KMS Encryption Context to use for object encryption. The value of this header is a base64-encoded
     * UTF-8 string holding JSON with the encryption context key-value pairs.
     *
     * @var string|null
     */
    private $sSEKMSEncryptionContext;

    /**
     * Specifies whether Amazon S3 should use an S3 Bucket Key for object encryption with server-side encryption using AWS
     * KMS (SSE-KMS). Setting this header to `true` causes Amazon S3 to use an S3 Bucket Key for object encryption with
     * SSE-KMS.
     *
     * @var bool|null
     */
    private $bucketKeyEnabled;

    /**
     * @var null|RequestPayer::*
     */
    private $requestPayer;

    /**
     * The tag-set for the object. The tag-set must be encoded as URL Query parameters. (For example, "Key1=Value1").
     *
     * @var string|null
     */
    private $tagging;

    /**
     * The Object Lock mode that you want to apply to this object.
     *
     * @var null|ObjectLockMode::*
     */
    private $objectLockMode;

    /**
     * The date and time when you want this object's Object Lock to expire.
     *
     * @var \DateTimeImmutable|null
     */
    private $objectLockRetainUntilDate;

    /**
     * Specifies whether a legal hold will be applied to this object. For more information about S3 Object Lock, see Object
     * Lock.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/object-lock.html
     *
     * @var null|ObjectLockLegalHoldStatus::*
     */
    private $objectLockLegalHoldStatus;

    /**
     * The account id of the expected bucket owner. If the bucket is owned by a different account, the request will fail
     * with an HTTP `403 (Access Denied)` error.
     *
     * @var string|null
     */
    private $expectedBucketOwner;

    /**
     * @param array{
     *   ACL?: ObjectCannedACL::*,
     *   Body?: string|resource|callable|iterable,
     *   Bucket?: string,
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
     *   BucketKeyEnabled?: bool,
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
        $this->aCL = $input['ACL'] ?? null;
        $this->body = $input['Body'] ?? null;
        $this->bucket = $input['Bucket'] ?? null;
        $this->cacheControl = $input['CacheControl'] ?? null;
        $this->contentDisposition = $input['ContentDisposition'] ?? null;
        $this->contentEncoding = $input['ContentEncoding'] ?? null;
        $this->contentLanguage = $input['ContentLanguage'] ?? null;
        $this->contentLength = $input['ContentLength'] ?? null;
        $this->contentMD5 = $input['ContentMD5'] ?? null;
        $this->contentType = $input['ContentType'] ?? null;
        $this->expires = !isset($input['Expires']) ? null : ($input['Expires'] instanceof \DateTimeImmutable ? $input['Expires'] : new \DateTimeImmutable($input['Expires']));
        $this->grantFullControl = $input['GrantFullControl'] ?? null;
        $this->grantRead = $input['GrantRead'] ?? null;
        $this->grantReadACP = $input['GrantReadACP'] ?? null;
        $this->grantWriteACP = $input['GrantWriteACP'] ?? null;
        $this->key = $input['Key'] ?? null;
        $this->metadata = $input['Metadata'] ?? null;
        $this->serverSideEncryption = $input['ServerSideEncryption'] ?? null;
        $this->storageClass = $input['StorageClass'] ?? null;
        $this->websiteRedirectLocation = $input['WebsiteRedirectLocation'] ?? null;
        $this->sSECustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->sSECustomerKey = $input['SSECustomerKey'] ?? null;
        $this->sSECustomerKeyMD5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->sSEKMSKeyId = $input['SSEKMSKeyId'] ?? null;
        $this->sSEKMSEncryptionContext = $input['SSEKMSEncryptionContext'] ?? null;
        $this->bucketKeyEnabled = $input['BucketKeyEnabled'] ?? null;
        $this->requestPayer = $input['RequestPayer'] ?? null;
        $this->tagging = $input['Tagging'] ?? null;
        $this->objectLockMode = $input['ObjectLockMode'] ?? null;
        $this->objectLockRetainUntilDate = !isset($input['ObjectLockRetainUntilDate']) ? null : ($input['ObjectLockRetainUntilDate'] instanceof \DateTimeImmutable ? $input['ObjectLockRetainUntilDate'] : new \DateTimeImmutable($input['ObjectLockRetainUntilDate']));
        $this->objectLockLegalHoldStatus = $input['ObjectLockLegalHoldStatus'] ?? null;
        $this->expectedBucketOwner = $input['ExpectedBucketOwner'] ?? null;
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
        return $this->aCL;
    }

    /**
     * @return string|resource|callable|iterable|null
     */
    public function getBody()
    {
        return $this->body;
    }

    public function getBucket(): ?string
    {
        return $this->bucket;
    }

    public function getBucketKeyEnabled(): ?bool
    {
        return $this->bucketKeyEnabled;
    }

    public function getCacheControl(): ?string
    {
        return $this->cacheControl;
    }

    public function getContentDisposition(): ?string
    {
        return $this->contentDisposition;
    }

    public function getContentEncoding(): ?string
    {
        return $this->contentEncoding;
    }

    public function getContentLanguage(): ?string
    {
        return $this->contentLanguage;
    }

    public function getContentLength(): ?string
    {
        return $this->contentLength;
    }

    public function getContentMD5(): ?string
    {
        return $this->contentMD5;
    }

    public function getContentType(): ?string
    {
        return $this->contentType;
    }

    public function getExpectedBucketOwner(): ?string
    {
        return $this->expectedBucketOwner;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->expires;
    }

    public function getGrantFullControl(): ?string
    {
        return $this->grantFullControl;
    }

    public function getGrantRead(): ?string
    {
        return $this->grantRead;
    }

    public function getGrantReadACP(): ?string
    {
        return $this->grantReadACP;
    }

    public function getGrantWriteACP(): ?string
    {
        return $this->grantWriteACP;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        return $this->metadata ?? [];
    }

    /**
     * @return ObjectLockLegalHoldStatus::*|null
     */
    public function getObjectLockLegalHoldStatus(): ?string
    {
        return $this->objectLockLegalHoldStatus;
    }

    /**
     * @return ObjectLockMode::*|null
     */
    public function getObjectLockMode(): ?string
    {
        return $this->objectLockMode;
    }

    public function getObjectLockRetainUntilDate(): ?\DateTimeImmutable
    {
        return $this->objectLockRetainUntilDate;
    }

    /**
     * @return RequestPayer::*|null
     */
    public function getRequestPayer(): ?string
    {
        return $this->requestPayer;
    }

    public function getSSECustomerAlgorithm(): ?string
    {
        return $this->sSECustomerAlgorithm;
    }

    public function getSSECustomerKey(): ?string
    {
        return $this->sSECustomerKey;
    }

    public function getSSECustomerKeyMD5(): ?string
    {
        return $this->sSECustomerKeyMD5;
    }

    public function getSSEKMSEncryptionContext(): ?string
    {
        return $this->sSEKMSEncryptionContext;
    }

    public function getSSEKMSKeyId(): ?string
    {
        return $this->sSEKMSKeyId;
    }

    /**
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        return $this->serverSideEncryption;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        return $this->storageClass;
    }

    public function getTagging(): ?string
    {
        return $this->tagging;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        return $this->websiteRedirectLocation;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [];
        if (null !== $this->aCL) {
            if (!ObjectCannedACL::exists($this->aCL)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ACL" for "%s". The value "%s" is not a valid "ObjectCannedACL".', __CLASS__, $this->aCL));
            }
            $headers['x-amz-acl'] = $this->aCL;
        }
        if (null !== $this->cacheControl) {
            $headers['Cache-Control'] = $this->cacheControl;
        }
        if (null !== $this->contentDisposition) {
            $headers['Content-Disposition'] = $this->contentDisposition;
        }
        if (null !== $this->contentEncoding) {
            $headers['Content-Encoding'] = $this->contentEncoding;
        }
        if (null !== $this->contentLanguage) {
            $headers['Content-Language'] = $this->contentLanguage;
        }
        if (null !== $this->contentLength) {
            $headers['Content-Length'] = $this->contentLength;
        }
        if (null !== $this->contentMD5) {
            $headers['Content-MD5'] = $this->contentMD5;
        }
        if (null !== $this->contentType) {
            $headers['Content-Type'] = $this->contentType;
        }
        if (null !== $this->expires) {
            $headers['Expires'] = $this->expires->format(\DateTimeInterface::RFC822);
        }
        if (null !== $this->grantFullControl) {
            $headers['x-amz-grant-full-control'] = $this->grantFullControl;
        }
        if (null !== $this->grantRead) {
            $headers['x-amz-grant-read'] = $this->grantRead;
        }
        if (null !== $this->grantReadACP) {
            $headers['x-amz-grant-read-acp'] = $this->grantReadACP;
        }
        if (null !== $this->grantWriteACP) {
            $headers['x-amz-grant-write-acp'] = $this->grantWriteACP;
        }
        if (null !== $this->serverSideEncryption) {
            if (!ServerSideEncryption::exists($this->serverSideEncryption)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ServerSideEncryption" for "%s". The value "%s" is not a valid "ServerSideEncryption".', __CLASS__, $this->serverSideEncryption));
            }
            $headers['x-amz-server-side-encryption'] = $this->serverSideEncryption;
        }
        if (null !== $this->storageClass) {
            if (!StorageClass::exists($this->storageClass)) {
                throw new InvalidArgument(sprintf('Invalid parameter "StorageClass" for "%s". The value "%s" is not a valid "StorageClass".', __CLASS__, $this->storageClass));
            }
            $headers['x-amz-storage-class'] = $this->storageClass;
        }
        if (null !== $this->websiteRedirectLocation) {
            $headers['x-amz-website-redirect-location'] = $this->websiteRedirectLocation;
        }
        if (null !== $this->sSECustomerAlgorithm) {
            $headers['x-amz-server-side-encryption-customer-algorithm'] = $this->sSECustomerAlgorithm;
        }
        if (null !== $this->sSECustomerKey) {
            $headers['x-amz-server-side-encryption-customer-key'] = $this->sSECustomerKey;
        }
        if (null !== $this->sSECustomerKeyMD5) {
            $headers['x-amz-server-side-encryption-customer-key-MD5'] = $this->sSECustomerKeyMD5;
        }
        if (null !== $this->sSEKMSKeyId) {
            $headers['x-amz-server-side-encryption-aws-kms-key-id'] = $this->sSEKMSKeyId;
        }
        if (null !== $this->sSEKMSEncryptionContext) {
            $headers['x-amz-server-side-encryption-context'] = $this->sSEKMSEncryptionContext;
        }
        if (null !== $this->bucketKeyEnabled) {
            $headers['x-amz-server-side-encryption-bucket-key-enabled'] = $this->bucketKeyEnabled ? 'true' : 'false';
        }
        if (null !== $this->requestPayer) {
            if (!RequestPayer::exists($this->requestPayer)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RequestPayer" for "%s". The value "%s" is not a valid "RequestPayer".', __CLASS__, $this->requestPayer));
            }
            $headers['x-amz-request-payer'] = $this->requestPayer;
        }
        if (null !== $this->tagging) {
            $headers['x-amz-tagging'] = $this->tagging;
        }
        if (null !== $this->objectLockMode) {
            if (!ObjectLockMode::exists($this->objectLockMode)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ObjectLockMode" for "%s". The value "%s" is not a valid "ObjectLockMode".', __CLASS__, $this->objectLockMode));
            }
            $headers['x-amz-object-lock-mode'] = $this->objectLockMode;
        }
        if (null !== $this->objectLockRetainUntilDate) {
            $headers['x-amz-object-lock-retain-until-date'] = $this->objectLockRetainUntilDate->format(\DateTimeInterface::ISO8601);
        }
        if (null !== $this->objectLockLegalHoldStatus) {
            if (!ObjectLockLegalHoldStatus::exists($this->objectLockLegalHoldStatus)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ObjectLockLegalHoldStatus" for "%s". The value "%s" is not a valid "ObjectLockLegalHoldStatus".', __CLASS__, $this->objectLockLegalHoldStatus));
            }
            $headers['x-amz-object-lock-legal-hold'] = $this->objectLockLegalHoldStatus;
        }
        if (null !== $this->expectedBucketOwner) {
            $headers['x-amz-expected-bucket-owner'] = $this->expectedBucketOwner;
        }
        if (null !== $this->metadata) {
            foreach ($this->metadata as $key => $value) {
                $headers["x-amz-meta-$key"] = $value;
            }
        }

        // Prepare query
        $query = [];

        // Prepare URI
        $uri = [];
        if (null === $v = $this->bucket) {
            throw new InvalidArgument(sprintf('Missing parameter "Bucket" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Bucket'] = $v;
        if (null === $v = $this->key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $uri['Key'] = $v;
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key']));

        // Prepare Body
        $body = $this->body ?? '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param ObjectCannedACL::*|null $value
     */
    public function setACL(?string $value): self
    {
        $this->aCL = $value;

        return $this;
    }

    /**
     * @param string|resource|callable|iterable|null $value
     */
    public function setBody($value): self
    {
        $this->body = $value;

        return $this;
    }

    public function setBucket(?string $value): self
    {
        $this->bucket = $value;

        return $this;
    }

    public function setBucketKeyEnabled(?bool $value): self
    {
        $this->bucketKeyEnabled = $value;

        return $this;
    }

    public function setCacheControl(?string $value): self
    {
        $this->cacheControl = $value;

        return $this;
    }

    public function setContentDisposition(?string $value): self
    {
        $this->contentDisposition = $value;

        return $this;
    }

    public function setContentEncoding(?string $value): self
    {
        $this->contentEncoding = $value;

        return $this;
    }

    public function setContentLanguage(?string $value): self
    {
        $this->contentLanguage = $value;

        return $this;
    }

    public function setContentLength(?string $value): self
    {
        $this->contentLength = $value;

        return $this;
    }

    public function setContentMD5(?string $value): self
    {
        $this->contentMD5 = $value;

        return $this;
    }

    public function setContentType(?string $value): self
    {
        $this->contentType = $value;

        return $this;
    }

    public function setExpectedBucketOwner(?string $value): self
    {
        $this->expectedBucketOwner = $value;

        return $this;
    }

    public function setExpires(?\DateTimeImmutable $value): self
    {
        $this->expires = $value;

        return $this;
    }

    public function setGrantFullControl(?string $value): self
    {
        $this->grantFullControl = $value;

        return $this;
    }

    public function setGrantRead(?string $value): self
    {
        $this->grantRead = $value;

        return $this;
    }

    public function setGrantReadACP(?string $value): self
    {
        $this->grantReadACP = $value;

        return $this;
    }

    public function setGrantWriteACP(?string $value): self
    {
        $this->grantWriteACP = $value;

        return $this;
    }

    public function setKey(?string $value): self
    {
        $this->key = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setMetadata(array $value): self
    {
        $this->metadata = $value;

        return $this;
    }

    /**
     * @param ObjectLockLegalHoldStatus::*|null $value
     */
    public function setObjectLockLegalHoldStatus(?string $value): self
    {
        $this->objectLockLegalHoldStatus = $value;

        return $this;
    }

    /**
     * @param ObjectLockMode::*|null $value
     */
    public function setObjectLockMode(?string $value): self
    {
        $this->objectLockMode = $value;

        return $this;
    }

    public function setObjectLockRetainUntilDate(?\DateTimeImmutable $value): self
    {
        $this->objectLockRetainUntilDate = $value;

        return $this;
    }

    /**
     * @param RequestPayer::*|null $value
     */
    public function setRequestPayer(?string $value): self
    {
        $this->requestPayer = $value;

        return $this;
    }

    public function setSSECustomerAlgorithm(?string $value): self
    {
        $this->sSECustomerAlgorithm = $value;

        return $this;
    }

    public function setSSECustomerKey(?string $value): self
    {
        $this->sSECustomerKey = $value;

        return $this;
    }

    public function setSSECustomerKeyMD5(?string $value): self
    {
        $this->sSECustomerKeyMD5 = $value;

        return $this;
    }

    public function setSSEKMSEncryptionContext(?string $value): self
    {
        $this->sSEKMSEncryptionContext = $value;

        return $this;
    }

    public function setSSEKMSKeyId(?string $value): self
    {
        $this->sSEKMSKeyId = $value;

        return $this;
    }

    /**
     * @param ServerSideEncryption::*|null $value
     */
    public function setServerSideEncryption(?string $value): self
    {
        $this->serverSideEncryption = $value;

        return $this;
    }

    /**
     * @param StorageClass::*|null $value
     */
    public function setStorageClass(?string $value): self
    {
        $this->storageClass = $value;

        return $this;
    }

    public function setTagging(?string $value): self
    {
        $this->tagging = $value;

        return $this;
    }

    public function setWebsiteRedirectLocation(?string $value): self
    {
        $this->websiteRedirectLocation = $value;

        return $this;
    }
}
