<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Stream\ResultStream;
use AsyncAws\S3\Enum\ChecksumType;
use AsyncAws\S3\Enum\ObjectLockLegalHoldStatus;
use AsyncAws\S3\Enum\ObjectLockMode;
use AsyncAws\S3\Enum\ReplicationStatus;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Enum\StorageClass;

class GetObjectOutput extends Result
{
    /**
     * Object data.
     *
     * @var ResultStream
     */
    private $body;

    /**
     * Indicates whether the object retrieved was (true) or was not (false) a Delete Marker. If false, this response header
     * does not appear in the response.
     *
     * > - If the current version of the object is a delete marker, Amazon S3 behaves as if the object was deleted and
     * >   includes `x-amz-delete-marker: true` in the response.
     * > - If the specified version in the request is a delete marker, the response returns a `405 Method Not Allowed` error
     * >   and the `Last-Modified: timestamp` response header.
     * >
     *
     * @var bool|null
     */
    private $deleteMarker;

    /**
     * Indicates that a range of bytes was specified in the request.
     *
     * @var string|null
     */
    private $acceptRanges;

    /**
     * If the object expiration is configured (see `PutBucketLifecycleConfiguration` [^1]), the response includes this
     * header. It includes the `expiry-date` and `rule-id` key-value pairs providing object expiration information. The
     * value of the `rule-id` is URL-encoded.
     *
     * > Object expiration information is not returned in directory buckets and this header returns the value
     * > "`NotImplemented`" in all responses for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketLifecycleConfiguration.html
     *
     * @var string|null
     */
    private $expiration;

    /**
     * Provides information about object restoration action and expiration time of the restored object copy.
     *
     * > This functionality is not supported for directory buckets. Directory buckets only support `EXPRESS_ONEZONE` (the S3
     * > Express One Zone storage class) in Availability Zones and `ONEZONE_IA` (the S3 One Zone-Infrequent Access storage
     * > class) in Dedicated Local Zones.
     *
     * @var string|null
     */
    private $restore;

    /**
     * Date and time when the object was last modified.
     *
     * **General purpose buckets ** - When you specify a `versionId` of the object in your request, if the specified version
     * in the request is a delete marker, the response returns a `405 Method Not Allowed` error and the `Last-Modified:
     * timestamp` response header.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastModified;

    /**
     * Size of the body in bytes.
     *
     * @var int|null
     */
    private $contentLength;

    /**
     * An entity tag (ETag) is an opaque identifier assigned by a web server to a specific version of a resource found at a
     * URL.
     *
     * @var string|null
     */
    private $etag;

    /**
     * The Base64 encoded, 32-bit `CRC32` checksum of the object. This checksum is only present if the object was uploaded
     * with the object. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * The Base64 encoded, 32-bit `CRC32C` checksum of the object. This will only be present if the object was uploaded with
     * the object. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * The Base64 encoded, 64-bit `CRC64NVME` checksum of the object. For more information, see Checking object integrity in
     * the Amazon S3 User Guide [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc64Nvme;

    /**
     * The Base64 encoded, 160-bit `SHA1` digest of the object. This will only be present if the object was uploaded with
     * the object. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * The Base64 encoded, 256-bit `SHA256` digest of the object. This will only be present if the object was uploaded with
     * the object. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * The checksum type, which determines how part-level checksums are combined to create an object-level checksum for
     * multipart objects. You can use this header response to verify that the checksum type that is received is the same
     * checksum type that was specified in the `CreateMultipartUpload` request. For more information, see Checking object
     * integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var ChecksumType::*|null
     */
    private $checksumType;

    /**
     * This is set to the number of metadata entries not returned in the headers that are prefixed with `x-amz-meta-`. This
     * can happen if you create metadata using an API like SOAP that supports more flexible metadata than the REST API. For
     * example, using SOAP, you can create metadata whose values are not legal HTTP headers.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var int|null
     */
    private $missingMeta;

    /**
     * Version ID of the object.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * Specifies caching behavior along the request/reply chain.
     *
     * @var string|null
     */
    private $cacheControl;

    /**
     * Specifies presentational information for the object.
     *
     * @var string|null
     */
    private $contentDisposition;

    /**
     * Indicates what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field.
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
     * The portion of the object returned in the response.
     *
     * @var string|null
     */
    private $contentRange;

    /**
     * A standard MIME type describing the format of the object data.
     *
     * @var string|null
     */
    private $contentType;

    /**
     * The date and time at which the object is no longer cacheable.
     *
     * @var \DateTimeImmutable|null
     */
    private $expires;

    /**
     * If the bucket is configured as a website, redirects requests for this object to another object in the same bucket or
     * to an external URL. Amazon S3 stores the value of this header in the object metadata.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $websiteRedirectLocation;

    /**
     * The server-side encryption algorithm used when you store this object in Amazon S3 or Amazon FSx.
     *
     * > When accessing data stored in Amazon FSx file systems using S3 access points, the only valid server side encryption
     * > option is `aws:fsx`.
     *
     * @var ServerSideEncryption::*|null
     */
    private $serverSideEncryption;

    /**
     * A map of metadata to store with the object in S3.
     *
     * @var array<string, string>
     */
    private $metadata;

    /**
     * If server-side encryption with a customer-provided encryption key was requested, the response will include this
     * header to confirm the encryption algorithm that's used.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $sseCustomerAlgorithm;

    /**
     * If server-side encryption with a customer-provided encryption key was requested, the response will include this
     * header to provide the round-trip message integrity verification of the customer-provided encryption key.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $sseCustomerKeyMd5;

    /**
     * If present, indicates the ID of the KMS key that was used for object encryption.
     *
     * @var string|null
     */
    private $sseKmsKeyId;

    /**
     * Indicates whether the object uses an S3 Bucket Key for server-side encryption with Key Management Service (KMS) keys
     * (SSE-KMS).
     *
     * @var bool|null
     */
    private $bucketKeyEnabled;

    /**
     * Provides storage class information of the object. Amazon S3 returns this header for all objects except for S3
     * Standard storage class objects.
     *
     * > **Directory buckets ** - Directory buckets only support `EXPRESS_ONEZONE` (the S3 Express One Zone storage class)
     * > in Availability Zones and `ONEZONE_IA` (the S3 One Zone-Infrequent Access storage class) in Dedicated Local Zones.
     *
     * @var StorageClass::*|null
     */
    private $storageClass;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    /**
     * Amazon S3 can return this if your request involves a bucket that is either a source or destination in a replication
     * rule.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var ReplicationStatus::*|null
     */
    private $replicationStatus;

    /**
     * The count of parts this object has. This value is only returned if you specify `partNumber` in your request and the
     * object was uploaded as a multipart upload.
     *
     * @var int|null
     */
    private $partsCount;

    /**
     * The number of tags, if any, on the object, when you have the relevant permission to read object tags.
     *
     * You can use GetObjectTagging [^1] to retrieve the tag set associated with an object.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetObjectTagging.html
     *
     * @var int|null
     */
    private $tagCount;

    /**
     * The Object Lock mode that's currently in place for this object.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var ObjectLockMode::*|null
     */
    private $objectLockMode;

    /**
     * The date and time when this object's Object Lock will expire.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var \DateTimeImmutable|null
     */
    private $objectLockRetainUntilDate;

    /**
     * Indicates whether this object has an active legal hold. This field is only returned if you have permission to view an
     * object's legal hold status.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var ObjectLockLegalHoldStatus::*|null
     */
    private $objectLockLegalHoldStatus;

    public function getAcceptRanges(): ?string
    {
        $this->initialize();

        return $this->acceptRanges;
    }

    public function getBody(): ResultStream
    {
        $this->initialize();

        return $this->body;
    }

    public function getBucketKeyEnabled(): ?bool
    {
        $this->initialize();

        return $this->bucketKeyEnabled;
    }

    public function getCacheControl(): ?string
    {
        $this->initialize();

        return $this->cacheControl;
    }

    public function getChecksumCrc32(): ?string
    {
        $this->initialize();

        return $this->checksumCrc32;
    }

    public function getChecksumCrc32C(): ?string
    {
        $this->initialize();

        return $this->checksumCrc32C;
    }

    public function getChecksumCrc64Nvme(): ?string
    {
        $this->initialize();

        return $this->checksumCrc64Nvme;
    }

    public function getChecksumSha1(): ?string
    {
        $this->initialize();

        return $this->checksumSha1;
    }

    public function getChecksumSha256(): ?string
    {
        $this->initialize();

        return $this->checksumSha256;
    }

    /**
     * @return ChecksumType::*|null
     */
    public function getChecksumType(): ?string
    {
        $this->initialize();

        return $this->checksumType;
    }

    public function getContentDisposition(): ?string
    {
        $this->initialize();

        return $this->contentDisposition;
    }

    public function getContentEncoding(): ?string
    {
        $this->initialize();

        return $this->contentEncoding;
    }

    public function getContentLanguage(): ?string
    {
        $this->initialize();

        return $this->contentLanguage;
    }

    public function getContentLength(): ?int
    {
        $this->initialize();

        return $this->contentLength;
    }

    public function getContentRange(): ?string
    {
        $this->initialize();

        return $this->contentRange;
    }

    public function getContentType(): ?string
    {
        $this->initialize();

        return $this->contentType;
    }

    public function getDeleteMarker(): ?bool
    {
        $this->initialize();

        return $this->deleteMarker;
    }

    public function getEtag(): ?string
    {
        $this->initialize();

        return $this->etag;
    }

    public function getExpiration(): ?string
    {
        $this->initialize();

        return $this->expiration;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->expires;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->lastModified;
    }

    /**
     * @return array<string, string>
     */
    public function getMetadata(): array
    {
        $this->initialize();

        return $this->metadata;
    }

    public function getMissingMeta(): ?int
    {
        $this->initialize();

        return $this->missingMeta;
    }

    /**
     * @return ObjectLockLegalHoldStatus::*|null
     */
    public function getObjectLockLegalHoldStatus(): ?string
    {
        $this->initialize();

        return $this->objectLockLegalHoldStatus;
    }

    /**
     * @return ObjectLockMode::*|null
     */
    public function getObjectLockMode(): ?string
    {
        $this->initialize();

        return $this->objectLockMode;
    }

    public function getObjectLockRetainUntilDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->objectLockRetainUntilDate;
    }

    public function getPartsCount(): ?int
    {
        $this->initialize();

        return $this->partsCount;
    }

    /**
     * @return ReplicationStatus::*|null
     */
    public function getReplicationStatus(): ?string
    {
        $this->initialize();

        return $this->replicationStatus;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->requestCharged;
    }

    public function getRestore(): ?string
    {
        $this->initialize();

        return $this->restore;
    }

    /**
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->serverSideEncryption;
    }

    public function getSseCustomerAlgorithm(): ?string
    {
        $this->initialize();

        return $this->sseCustomerAlgorithm;
    }

    public function getSseCustomerKeyMd5(): ?string
    {
        $this->initialize();

        return $this->sseCustomerKeyMd5;
    }

    public function getSseKmsKeyId(): ?string
    {
        $this->initialize();

        return $this->sseKmsKeyId;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        $this->initialize();

        return $this->storageClass;
    }

    public function getTagCount(): ?int
    {
        $this->initialize();

        return $this->tagCount;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->versionId;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        $this->initialize();

        return $this->websiteRedirectLocation;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->deleteMarker = isset($headers['x-amz-delete-marker'][0]) ? filter_var($headers['x-amz-delete-marker'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->acceptRanges = $headers['accept-ranges'][0] ?? null;
        $this->expiration = $headers['x-amz-expiration'][0] ?? null;
        $this->restore = $headers['x-amz-restore'][0] ?? null;
        $this->lastModified = isset($headers['last-modified'][0]) ? new \DateTimeImmutable($headers['last-modified'][0]) : null;
        $this->contentLength = isset($headers['content-length'][0]) ? (int) $headers['content-length'][0] : null;
        $this->etag = $headers['etag'][0] ?? null;
        $this->checksumCrc32 = $headers['x-amz-checksum-crc32'][0] ?? null;
        $this->checksumCrc32C = $headers['x-amz-checksum-crc32c'][0] ?? null;
        $this->checksumCrc64Nvme = $headers['x-amz-checksum-crc64nvme'][0] ?? null;
        $this->checksumSha1 = $headers['x-amz-checksum-sha1'][0] ?? null;
        $this->checksumSha256 = $headers['x-amz-checksum-sha256'][0] ?? null;
        $this->checksumType = $headers['x-amz-checksum-type'][0] ?? null;
        $this->missingMeta = isset($headers['x-amz-missing-meta'][0]) ? (int) $headers['x-amz-missing-meta'][0] : null;
        $this->versionId = $headers['x-amz-version-id'][0] ?? null;
        $this->cacheControl = $headers['cache-control'][0] ?? null;
        $this->contentDisposition = $headers['content-disposition'][0] ?? null;
        $this->contentEncoding = $headers['content-encoding'][0] ?? null;
        $this->contentLanguage = $headers['content-language'][0] ?? null;
        $this->contentRange = $headers['content-range'][0] ?? null;
        $this->contentType = $headers['content-type'][0] ?? null;
        $this->expires = isset($headers['expires'][0]) ? new \DateTimeImmutable($headers['expires'][0]) : null;
        $this->websiteRedirectLocation = $headers['x-amz-website-redirect-location'][0] ?? null;
        $this->serverSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->sseCustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->sseCustomerKeyMd5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->sseKmsKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->bucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->storageClass = $headers['x-amz-storage-class'][0] ?? null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;
        $this->replicationStatus = $headers['x-amz-replication-status'][0] ?? null;
        $this->partsCount = isset($headers['x-amz-mp-parts-count'][0]) ? (int) $headers['x-amz-mp-parts-count'][0] : null;
        $this->tagCount = isset($headers['x-amz-tagging-count'][0]) ? (int) $headers['x-amz-tagging-count'][0] : null;
        $this->objectLockMode = $headers['x-amz-object-lock-mode'][0] ?? null;
        $this->objectLockRetainUntilDate = isset($headers['x-amz-object-lock-retain-until-date'][0]) ? new \DateTimeImmutable($headers['x-amz-object-lock-retain-until-date'][0]) : null;
        $this->objectLockLegalHoldStatus = $headers['x-amz-object-lock-legal-hold'][0] ?? null;

        $this->metadata = [];
        foreach ($headers as $name => $value) {
            if ('x-amz-meta-' === substr($name, 0, 11)) {
                $this->metadata[substr($name, 11)] = $value[0];
            }
        }

        $this->body = $response->toStream();
    }
}
