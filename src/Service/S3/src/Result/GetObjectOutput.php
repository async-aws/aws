<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\Core\Stream\ResultStream;
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
     */
    private $Body;

    /**
     * Specifies whether the object retrieved was (true) or was not (false) a Delete Marker. If false, this response header
     * does not appear in the response.
     */
    private $DeleteMarker;

    /**
     * Indicates that a range of bytes was specified.
     */
    private $AcceptRanges;

    /**
     * If the object expiration is configured (see PUT Bucket lifecycle), the response includes this header. It includes the
     * expiry-date and rule-id key-value pairs providing object expiration information. The value of the rule-id is URL
     * encoded.
     */
    private $Expiration;

    /**
     * Provides information about object restoration operation and expiration time of the restored object copy.
     */
    private $Restore;

    /**
     * Last modified date of the object.
     */
    private $LastModified;

    /**
     * Size of the body in bytes.
     */
    private $ContentLength;

    /**
     * An ETag is an opaque identifier assigned by a web server to a specific version of a resource found at a URL.
     */
    private $ETag;

    /**
     * This is set to the number of metadata entries not returned in `x-amz-meta` headers. This can happen if you create
     * metadata using an API like SOAP that supports more flexible metadata than the REST API. For example, using SOAP, you
     * can create metadata whose values are not legal HTTP headers.
     */
    private $MissingMeta;

    /**
     * Version of the object.
     */
    private $VersionId;

    /**
     * Specifies caching behavior along the request/reply chain.
     */
    private $CacheControl;

    /**
     * Specifies presentational information for the object.
     */
    private $ContentDisposition;

    /**
     * Specifies what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field.
     */
    private $ContentEncoding;

    /**
     * The language the content is in.
     */
    private $ContentLanguage;

    /**
     * The portion of the object returned in the response.
     */
    private $ContentRange;

    /**
     * A standard MIME type describing the format of the object data.
     */
    private $ContentType;

    /**
     * The date and time at which the object is no longer cacheable.
     */
    private $Expires;

    /**
     * If the bucket is configured as a website, redirects requests for this object to another object in the same bucket or
     * to an external URL. Amazon S3 stores the value of this header in the object metadata.
     */
    private $WebsiteRedirectLocation;

    /**
     * The server-side encryption algorithm used when storing this object in Amazon S3 (for example, AES256, aws:kms).
     */
    private $ServerSideEncryption;

    /**
     * A map of metadata to store with the object in S3.
     */
    private $Metadata = [];

    /**
     * If server-side encryption with a customer-provided encryption key was requested, the response will include this
     * header confirming the encryption algorithm used.
     */
    private $SSECustomerAlgorithm;

    /**
     * If server-side encryption with a customer-provided encryption key was requested, the response will include this
     * header to provide round-trip message integrity verification of the customer-provided encryption key.
     */
    private $SSECustomerKeyMD5;

    /**
     * If present, specifies the ID of the AWS Key Management Service (AWS KMS) symmetric customer managed customer master
     * key (CMK) that was used for the object.
     */
    private $SSEKMSKeyId;

    /**
     * Provides storage class information of the object. Amazon S3 returns this header for all objects except for Standard
     * storage class objects.
     */
    private $StorageClass;

    private $RequestCharged;

    /**
     * Amazon S3 can return this if your request involves a bucket that is either a source or destination in a replication
     * rule.
     */
    private $ReplicationStatus;

    /**
     * The count of parts this object has.
     */
    private $PartsCount;

    /**
     * The number of tags, if any, on the object.
     */
    private $TagCount;

    /**
     * The Object Lock mode currently in place for this object.
     */
    private $ObjectLockMode;

    /**
     * The date and time when this object's Object Lock will expire.
     */
    private $ObjectLockRetainUntilDate;

    /**
     * Indicates whether this object has an active legal hold. This field is only returned if you have permission to view an
     * object's legal hold status.
     */
    private $ObjectLockLegalHoldStatus;

    public function getAcceptRanges(): ?string
    {
        $this->initialize();

        return $this->AcceptRanges;
    }

    public function getBody(): ResultStream
    {
        $this->initialize();

        return $this->Body;
    }

    public function getCacheControl(): ?string
    {
        $this->initialize();

        return $this->CacheControl;
    }

    public function getContentDisposition(): ?string
    {
        $this->initialize();

        return $this->ContentDisposition;
    }

    public function getContentEncoding(): ?string
    {
        $this->initialize();

        return $this->ContentEncoding;
    }

    public function getContentLanguage(): ?string
    {
        $this->initialize();

        return $this->ContentLanguage;
    }

    public function getContentLength(): ?string
    {
        $this->initialize();

        return $this->ContentLength;
    }

    public function getContentRange(): ?string
    {
        $this->initialize();

        return $this->ContentRange;
    }

    public function getContentType(): ?string
    {
        $this->initialize();

        return $this->ContentType;
    }

    public function getDeleteMarker(): ?bool
    {
        $this->initialize();

        return $this->DeleteMarker;
    }

    public function getETag(): ?string
    {
        $this->initialize();

        return $this->ETag;
    }

    public function getExpiration(): ?string
    {
        $this->initialize();

        return $this->Expiration;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->Expires;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->LastModified;
    }

    /**
     * @return string[]
     */
    public function getMetadata(): array
    {
        $this->initialize();

        return $this->Metadata;
    }

    public function getMissingMeta(): ?int
    {
        $this->initialize();

        return $this->MissingMeta;
    }

    /**
     * @return ObjectLockLegalHoldStatus::*|null
     */
    public function getObjectLockLegalHoldStatus(): ?string
    {
        $this->initialize();

        return $this->ObjectLockLegalHoldStatus;
    }

    /**
     * @return ObjectLockMode::*|null
     */
    public function getObjectLockMode(): ?string
    {
        $this->initialize();

        return $this->ObjectLockMode;
    }

    public function getObjectLockRetainUntilDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->ObjectLockRetainUntilDate;
    }

    public function getPartsCount(): ?int
    {
        $this->initialize();

        return $this->PartsCount;
    }

    /**
     * @return ReplicationStatus::*|null
     */
    public function getReplicationStatus(): ?string
    {
        $this->initialize();

        return $this->ReplicationStatus;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    public function getRestore(): ?string
    {
        $this->initialize();

        return $this->Restore;
    }

    public function getSSECustomerAlgorithm(): ?string
    {
        $this->initialize();

        return $this->SSECustomerAlgorithm;
    }

    public function getSSECustomerKeyMD5(): ?string
    {
        $this->initialize();

        return $this->SSECustomerKeyMD5;
    }

    public function getSSEKMSKeyId(): ?string
    {
        $this->initialize();

        return $this->SSEKMSKeyId;
    }

    /**
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->ServerSideEncryption;
    }

    /**
     * @return StorageClass::*|null
     */
    public function getStorageClass(): ?string
    {
        $this->initialize();

        return $this->StorageClass;
    }

    public function getTagCount(): ?int
    {
        $this->initialize();

        return $this->TagCount;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        $this->initialize();

        return $this->WebsiteRedirectLocation;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->DeleteMarker = isset($headers['x-amz-delete-marker'][0]) ? filter_var($headers['x-amz-delete-marker'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->AcceptRanges = $headers['accept-ranges'][0] ?? null;
        $this->Expiration = $headers['x-amz-expiration'][0] ?? null;
        $this->Restore = $headers['x-amz-restore'][0] ?? null;
        $this->LastModified = isset($headers['last-modified'][0]) ? new \DateTimeImmutable($headers['last-modified'][0]) : null;
        $this->ContentLength = $headers['content-length'][0] ?? null;
        $this->ETag = $headers['etag'][0] ?? null;
        $this->MissingMeta = isset($headers['x-amz-missing-meta'][0]) ? filter_var($headers['x-amz-missing-meta'][0], \FILTER_VALIDATE_INT) : null;
        $this->VersionId = $headers['x-amz-version-id'][0] ?? null;
        $this->CacheControl = $headers['cache-control'][0] ?? null;
        $this->ContentDisposition = $headers['content-disposition'][0] ?? null;
        $this->ContentEncoding = $headers['content-encoding'][0] ?? null;
        $this->ContentLanguage = $headers['content-language'][0] ?? null;
        $this->ContentRange = $headers['content-range'][0] ?? null;
        $this->ContentType = $headers['content-type'][0] ?? null;
        $this->Expires = isset($headers['expires'][0]) ? new \DateTimeImmutable($headers['expires'][0]) : null;
        $this->WebsiteRedirectLocation = $headers['x-amz-website-redirect-location'][0] ?? null;
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->StorageClass = $headers['x-amz-storage-class'][0] ?? null;
        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;
        $this->ReplicationStatus = $headers['x-amz-replication-status'][0] ?? null;
        $this->PartsCount = isset($headers['x-amz-mp-parts-count'][0]) ? filter_var($headers['x-amz-mp-parts-count'][0], \FILTER_VALIDATE_INT) : null;
        $this->TagCount = isset($headers['x-amz-tagging-count'][0]) ? filter_var($headers['x-amz-tagging-count'][0], \FILTER_VALIDATE_INT) : null;
        $this->ObjectLockMode = $headers['x-amz-object-lock-mode'][0] ?? null;
        $this->ObjectLockRetainUntilDate = isset($headers['x-amz-object-lock-retain-until-date'][0]) ? new \DateTimeImmutable($headers['x-amz-object-lock-retain-until-date'][0]) : null;
        $this->ObjectLockLegalHoldStatus = $headers['x-amz-object-lock-legal-hold'][0] ?? null;

        $this->Metadata = [];
        foreach ($headers as $name => $value) {
            if ('x-amz-meta-' === substr($name, 0, 11)) {
                $this->Metadata[$name] = $value[0];
            }
        }

        $this->Body = $response->toStream();
    }
}
