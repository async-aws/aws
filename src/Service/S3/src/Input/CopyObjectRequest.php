<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\S3\Enum\MetadataDirective;
use AsyncAws\S3\Enum\ObjectCannedACL;
use AsyncAws\S3\Enum\ObjectLockLegalHoldStatus;
use AsyncAws\S3\Enum\ObjectLockMode;
use AsyncAws\S3\Enum\RequestPayer;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Enum\StorageClass;
use AsyncAws\S3\Enum\TaggingDirective;

class CopyObjectRequest implements Input
{
    /**
     * The canned ACL to apply to the object.
     *
     * @var null|ObjectCannedACL::*
     */
    private $ACL;

    /**
     * The name of the destination bucket.
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
     * The name of the source bucket and key name of the source object, separated by a slash (/). Must be URL-encoded.
     *
     * @required
     *
     * @var string|null
     */
    private $CopySource;

    /**
     * Copies the object if its entity tag (ETag) matches the specified tag.
     *
     * @var string|null
     */
    private $CopySourceIfMatch;

    /**
     * Copies the object if it has been modified since the specified time.
     *
     * @var \DateTimeImmutable|null
     */
    private $CopySourceIfModifiedSince;

    /**
     * Copies the object if its entity tag (ETag) is different than the specified ETag.
     *
     * @var string|null
     */
    private $CopySourceIfNoneMatch;

    /**
     * Copies the object if it hasn't been modified since the specified time.
     *
     * @var \DateTimeImmutable|null
     */
    private $CopySourceIfUnmodifiedSince;

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
     * The key of the destination object.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * A map of metadata to store with the object in S3.
     *
     * @var string[]
     */
    private $Metadata;

    /**
     * Specifies whether the metadata is copied from the source object or replaced with metadata provided in the request.
     *
     * @var null|MetadataDirective::*
     */
    private $MetadataDirective;

    /**
     * Specifies whether the object tag-set are copied from the source object or replaced with tag-set provided in the
     * request.
     *
     * @var null|TaggingDirective::*
     */
    private $TaggingDirective;

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
     * with the algorithm specified in the `x-amz-server-side​-encryption​-customer-algorithm` header.
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
     * Specifies the AWS KMS key ID to use for object encryption. All GET and PUT requests for an object protected by AWS
     * KMS will fail if not made via SSL or using SigV4. For information about configuring using any of the officially
     * supported AWS SDKs and AWS CLI, see Specifying the Signature Version in Request Authentication in the *Amazon S3
     * Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingAWSSDK.html#specify-signature-version
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
     * Specifies the algorithm to use when decrypting the source object (for example, AES256).
     *
     * @var string|null
     */
    private $CopySourceSSECustomerAlgorithm;

    /**
     * Specifies the customer-provided encryption key for Amazon S3 to use to decrypt the source object. The encryption key
     * provided in this header must be one that was used when the source object was created.
     *
     * @var string|null
     */
    private $CopySourceSSECustomerKey;

    /**
     * Specifies the 128-bit MD5 digest of the encryption key according to RFC 1321. Amazon S3 uses this header for a
     * message integrity check to ensure that the encryption key was transmitted without error.
     *
     * @var string|null
     */
    private $CopySourceSSECustomerKeyMD5;

    /**
     * @var null|RequestPayer::*
     */
    private $RequestPayer;

    /**
     * The tag-set for the object destination object this value must be used in conjunction with the `TaggingDirective`. The
     * tag-set must be encoded as URL Query parameters.
     *
     * @var string|null
     */
    private $Tagging;

    /**
     * The Object Lock mode that you want to apply to the copied object.
     *
     * @var null|ObjectLockMode::*
     */
    private $ObjectLockMode;

    /**
     * The date and time when you want the copied object's Object Lock to expire.
     *
     * @var \DateTimeImmutable|null
     */
    private $ObjectLockRetainUntilDate;

    /**
     * Specifies whether you want to apply a Legal Hold to the copied object.
     *
     * @var null|ObjectLockLegalHoldStatus::*
     */
    private $ObjectLockLegalHoldStatus;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectCOPY.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
     *   Bucket?: string,
     *   CacheControl?: string,
     *   ContentDisposition?: string,
     *   ContentEncoding?: string,
     *   ContentLanguage?: string,
     *   ContentType?: string,
     *   CopySource?: string,
     *   CopySourceIfMatch?: string,
     *   CopySourceIfModifiedSince?: \DateTimeImmutable|string,
     *   CopySourceIfNoneMatch?: string,
     *   CopySourceIfUnmodifiedSince?: \DateTimeImmutable|string,
     *   Expires?: \DateTimeImmutable|string,
     *   GrantFullControl?: string,
     *   GrantRead?: string,
     *   GrantReadACP?: string,
     *   GrantWriteACP?: string,
     *   Key?: string,
     *   Metadata?: string[],
     *   MetadataDirective?: \AsyncAws\S3\Enum\MetadataDirective::*,
     *   TaggingDirective?: \AsyncAws\S3\Enum\TaggingDirective::*,
     *   ServerSideEncryption?: \AsyncAws\S3\Enum\ServerSideEncryption::*,
     *   StorageClass?: \AsyncAws\S3\Enum\StorageClass::*,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   CopySourceSSECustomerAlgorithm?: string,
     *   CopySourceSSECustomerKey?: string,
     *   CopySourceSSECustomerKeyMD5?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   Tagging?: string,
     *   ObjectLockMode?: \AsyncAws\S3\Enum\ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: \AsyncAws\S3\Enum\ObjectLockLegalHoldStatus::*,
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
        $this->CopySource = $input['CopySource'] ?? null;
        $this->CopySourceIfMatch = $input['CopySourceIfMatch'] ?? null;
        $this->CopySourceIfModifiedSince = !isset($input['CopySourceIfModifiedSince']) ? null : ($input['CopySourceIfModifiedSince'] instanceof \DateTimeImmutable ? $input['CopySourceIfModifiedSince'] : new \DateTimeImmutable($input['CopySourceIfModifiedSince']));
        $this->CopySourceIfNoneMatch = $input['CopySourceIfNoneMatch'] ?? null;
        $this->CopySourceIfUnmodifiedSince = !isset($input['CopySourceIfUnmodifiedSince']) ? null : ($input['CopySourceIfUnmodifiedSince'] instanceof \DateTimeImmutable ? $input['CopySourceIfUnmodifiedSince'] : new \DateTimeImmutable($input['CopySourceIfUnmodifiedSince']));
        $this->Expires = !isset($input['Expires']) ? null : ($input['Expires'] instanceof \DateTimeImmutable ? $input['Expires'] : new \DateTimeImmutable($input['Expires']));
        $this->GrantFullControl = $input['GrantFullControl'] ?? null;
        $this->GrantRead = $input['GrantRead'] ?? null;
        $this->GrantReadACP = $input['GrantReadACP'] ?? null;
        $this->GrantWriteACP = $input['GrantWriteACP'] ?? null;
        $this->Key = $input['Key'] ?? null;
        $this->Metadata = $input['Metadata'] ?? [];
        $this->MetadataDirective = $input['MetadataDirective'] ?? null;
        $this->TaggingDirective = $input['TaggingDirective'] ?? null;
        $this->ServerSideEncryption = $input['ServerSideEncryption'] ?? null;
        $this->StorageClass = $input['StorageClass'] ?? null;
        $this->WebsiteRedirectLocation = $input['WebsiteRedirectLocation'] ?? null;
        $this->SSECustomerAlgorithm = $input['SSECustomerAlgorithm'] ?? null;
        $this->SSECustomerKey = $input['SSECustomerKey'] ?? null;
        $this->SSECustomerKeyMD5 = $input['SSECustomerKeyMD5'] ?? null;
        $this->SSEKMSKeyId = $input['SSEKMSKeyId'] ?? null;
        $this->SSEKMSEncryptionContext = $input['SSEKMSEncryptionContext'] ?? null;
        $this->CopySourceSSECustomerAlgorithm = $input['CopySourceSSECustomerAlgorithm'] ?? null;
        $this->CopySourceSSECustomerKey = $input['CopySourceSSECustomerKey'] ?? null;
        $this->CopySourceSSECustomerKeyMD5 = $input['CopySourceSSECustomerKeyMD5'] ?? null;
        $this->RequestPayer = $input['RequestPayer'] ?? null;
        $this->Tagging = $input['Tagging'] ?? null;
        $this->ObjectLockMode = $input['ObjectLockMode'] ?? null;
        $this->ObjectLockRetainUntilDate = !isset($input['ObjectLockRetainUntilDate']) ? null : ($input['ObjectLockRetainUntilDate'] instanceof \DateTimeImmutable ? $input['ObjectLockRetainUntilDate'] : new \DateTimeImmutable($input['ObjectLockRetainUntilDate']));
        $this->ObjectLockLegalHoldStatus = $input['ObjectLockLegalHoldStatus'] ?? null;
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

    public function getCopySource(): ?string
    {
        return $this->CopySource;
    }

    public function getCopySourceIfMatch(): ?string
    {
        return $this->CopySourceIfMatch;
    }

    public function getCopySourceIfModifiedSince(): ?\DateTimeImmutable
    {
        return $this->CopySourceIfModifiedSince;
    }

    public function getCopySourceIfNoneMatch(): ?string
    {
        return $this->CopySourceIfNoneMatch;
    }

    public function getCopySourceIfUnmodifiedSince(): ?\DateTimeImmutable
    {
        return $this->CopySourceIfUnmodifiedSince;
    }

    public function getCopySourceSSECustomerAlgorithm(): ?string
    {
        return $this->CopySourceSSECustomerAlgorithm;
    }

    public function getCopySourceSSECustomerKey(): ?string
    {
        return $this->CopySourceSSECustomerKey;
    }

    public function getCopySourceSSECustomerKeyMD5(): ?string
    {
        return $this->CopySourceSSECustomerKeyMD5;
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
     * @return string[]
     */
    public function getMetadata(): array
    {
        return $this->Metadata;
    }

    /**
     * @return MetadataDirective::*|null
     */
    public function getMetadataDirective(): ?string
    {
        return $this->MetadataDirective;
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

    /**
     * @return TaggingDirective::*|null
     */
    public function getTaggingDirective(): ?string
    {
        return $this->TaggingDirective;
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
        if (null === $v = $this->CopySource) {
            throw new InvalidArgument(sprintf('Missing parameter "CopySource" for "%s". The value cannot be null.', __CLASS__));
        }
        $headers['x-amz-copy-source'] = $v;
        if (null !== $this->CopySourceIfMatch) {
            $headers['x-amz-copy-source-if-match'] = $this->CopySourceIfMatch;
        }
        if (null !== $this->CopySourceIfModifiedSince) {
            $headers['x-amz-copy-source-if-modified-since'] = $this->CopySourceIfModifiedSince->format(\DateTimeInterface::RFC822);
        }
        if (null !== $this->CopySourceIfNoneMatch) {
            $headers['x-amz-copy-source-if-none-match'] = $this->CopySourceIfNoneMatch;
        }
        if (null !== $this->CopySourceIfUnmodifiedSince) {
            $headers['x-amz-copy-source-if-unmodified-since'] = $this->CopySourceIfUnmodifiedSince->format(\DateTimeInterface::RFC822);
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
        if (null !== $this->MetadataDirective) {
            if (!MetadataDirective::exists($this->MetadataDirective)) {
                throw new InvalidArgument(sprintf('Invalid parameter "MetadataDirective" for "%s". The value "%s" is not a valid "MetadataDirective".', __CLASS__, $this->MetadataDirective));
            }
            $headers['x-amz-metadata-directive'] = $this->MetadataDirective;
        }
        if (null !== $this->TaggingDirective) {
            if (!TaggingDirective::exists($this->TaggingDirective)) {
                throw new InvalidArgument(sprintf('Invalid parameter "TaggingDirective" for "%s". The value "%s" is not a valid "TaggingDirective".', __CLASS__, $this->TaggingDirective));
            }
            $headers['x-amz-tagging-directive'] = $this->TaggingDirective;
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
        if (null !== $this->CopySourceSSECustomerAlgorithm) {
            $headers['x-amz-copy-source-server-side-encryption-customer-algorithm'] = $this->CopySourceSSECustomerAlgorithm;
        }
        if (null !== $this->CopySourceSSECustomerKey) {
            $headers['x-amz-copy-source-server-side-encryption-customer-key'] = $this->CopySourceSSECustomerKey;
        }
        if (null !== $this->CopySourceSSECustomerKeyMD5) {
            $headers['x-amz-copy-source-server-side-encryption-customer-key-MD5'] = $this->CopySourceSSECustomerKeyMD5;
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
        $uriString = "/{$uri['Bucket']}/{$uri['Key']}";

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('PUT', $uriString, $query, $headers, StreamFactory::create($body));
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

    public function setCopySource(?string $value): self
    {
        $this->CopySource = $value;

        return $this;
    }

    public function setCopySourceIfMatch(?string $value): self
    {
        $this->CopySourceIfMatch = $value;

        return $this;
    }

    public function setCopySourceIfModifiedSince(?\DateTimeImmutable $value): self
    {
        $this->CopySourceIfModifiedSince = $value;

        return $this;
    }

    public function setCopySourceIfNoneMatch(?string $value): self
    {
        $this->CopySourceIfNoneMatch = $value;

        return $this;
    }

    public function setCopySourceIfUnmodifiedSince(?\DateTimeImmutable $value): self
    {
        $this->CopySourceIfUnmodifiedSince = $value;

        return $this;
    }

    public function setCopySourceSSECustomerAlgorithm(?string $value): self
    {
        $this->CopySourceSSECustomerAlgorithm = $value;

        return $this;
    }

    public function setCopySourceSSECustomerKey(?string $value): self
    {
        $this->CopySourceSSECustomerKey = $value;

        return $this;
    }

    public function setCopySourceSSECustomerKeyMD5(?string $value): self
    {
        $this->CopySourceSSECustomerKeyMD5 = $value;

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
     * @param string[] $value
     */
    public function setMetadata(array $value): self
    {
        $this->Metadata = $value;

        return $this;
    }

    /**
     * @param MetadataDirective::*|null $value
     */
    public function setMetadataDirective(?string $value): self
    {
        $this->MetadataDirective = $value;

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

    /**
     * @param TaggingDirective::*|null $value
     */
    public function setTaggingDirective(?string $value): self
    {
        $this->TaggingDirective = $value;

        return $this;
    }

    public function setWebsiteRedirectLocation(?string $value): self
    {
        $this->WebsiteRedirectLocation = $value;

        return $this;
    }
}
