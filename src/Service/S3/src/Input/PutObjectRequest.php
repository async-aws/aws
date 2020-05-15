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
    private $ACL;

    /**
     * Object data.
     *
     * @var string|resource|callable|iterable|null
     */
    private $Body;

    /**
     * Bucket name to which the PUT operation was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Bucket;

    /**
     * Can be used to specify caching behavior along the request/reply chain. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.9
     *
     * @var string|null
     */
    private $CacheControl;

    /**
     * Specifies presentational information for the object. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.5.1.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec19.html#sec19.5.1
     *
     * @var string|null
     */
    private $ContentDisposition;

    /**
     * Specifies what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.11.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.11
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
     * Size of the body in bytes. This parameter is useful when the size of the body cannot be determined automatically. For
     * more information, see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.13
     *
     * @var string|null
     */
    private $ContentLength;

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
    private $ContentMD5;

    /**
     * A standard MIME type describing the format of the contents. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.17
     *
     * @var string|null
     */
    private $ContentType;

    /**
     * The date and time at which the object is no longer cacheable. For more information, see
     * http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21.
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.21
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
     * Object key for which the PUT operation was initiated.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * A map of metadata to store with the object in S3.
     *
     * @var array<string, string>
     */
    private $Metadata;

    /**
     * The server-side encryption algorithm used when storing this object in Amazon S3 (for example, AES256, aws:kms).
     *
     * @var null|ServerSideEncryption::*
     */
    private $ServerSideEncryption;

    /**
     * If you don't specify, Standard is the default storage class. Amazon S3 supports other storage classes.
     *
     * @var null|StorageClass::*
     */
    private $StorageClass;

    /**
     * If the bucket is configured as a website, redirects requests for this object to another object in the same bucket or
     * to an external URL. Amazon S3 stores the value of this header in the object metadata. For information about object
     * metadata, see Object Key and Metadata.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/UsingMetadata.html
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
     * If `x-amz-server-side-encryption` is present and has the value of `aws:kms`, this header specifies the ID of the AWS
     * Key Management Service (AWS KMS) symmetrical customer managed customer master key (CMK) that was used for the object.
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
     * The tag-set for the object. The tag-set must be encoded as URL Query parameters. (For example, "Key1=Value1").
     *
     * @var string|null
     */
    private $Tagging;

    /**
     * The Object Lock mode that you want to apply to this object.
     *
     * @var null|ObjectLockMode::*
     */
    private $ObjectLockMode;

    /**
     * The date and time when you want this object's Object Lock to expire.
     *
     * @var \DateTimeImmutable|null
     */
    private $ObjectLockRetainUntilDate;

    /**
     * Specifies whether a legal hold will be applied to this object. For more information about S3 Object Lock, see Object
     * Lock.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/object-lock.html
     *
     * @var null|ObjectLockLegalHoldStatus::*
     */
    private $ObjectLockLegalHoldStatus;

    /**
     * @see http://docs.amazonwebservices.com/AmazonS3/latest/API/RESTObjectPUT.html
     *
     * @param array{
     *   ACL?: \AsyncAws\S3\Enum\ObjectCannedACL::*,
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
     *   ServerSideEncryption?: \AsyncAws\S3\Enum\ServerSideEncryption::*,
     *   StorageClass?: \AsyncAws\S3\Enum\StorageClass::*,
     *   WebsiteRedirectLocation?: string,
     *   SSECustomerAlgorithm?: string,
     *   SSECustomerKey?: string,
     *   SSECustomerKeyMD5?: string,
     *   SSEKMSKeyId?: string,
     *   SSEKMSEncryptionContext?: string,
     *   RequestPayer?: \AsyncAws\S3\Enum\RequestPayer::*,
     *   Tagging?: string,
     *   ObjectLockMode?: \AsyncAws\S3\Enum\ObjectLockMode::*,
     *   ObjectLockRetainUntilDate?: \DateTimeImmutable|string,
     *   ObjectLockLegalHoldStatus?: \AsyncAws\S3\Enum\ObjectLockLegalHoldStatus::*,
     *   @region?: string,
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
        $this->Metadata = $input['Metadata'] ?? [];
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

    /**
     * @return string|resource|callable|iterable|null
     */
    public function getBody()
    {
        return $this->Body;
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

    public function getContentLength(): ?string
    {
        return $this->ContentLength;
    }

    public function getContentMD5(): ?string
    {
        return $this->ContentMD5;
    }

    public function getContentType(): ?string
    {
        return $this->ContentType;
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
        return $this->Metadata;
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
        $headers = [];
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
        $uriString = '/' . rawurlencode($uri['Bucket']) . '/' . str_replace('%2F', '/', rawurlencode($uri['Key']));

        // Prepare Body
        $body = $this->Body ?? '';

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

    /**
     * @param string|resource|callable|iterable|null $value
     */
    public function setBody($value): self
    {
        $this->Body = $value;

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

    public function setContentLength(?string $value): self
    {
        $this->ContentLength = $value;

        return $this;
    }

    public function setContentMD5(?string $value): self
    {
        $this->ContentMD5 = $value;

        return $this;
    }

    public function setContentType(?string $value): self
    {
        $this->ContentType = $value;

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
