<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\ObjectLockLegalHoldStatus;
use AsyncAws\S3\Enum\ObjectLockMode;
use AsyncAws\S3\Enum\ReplicationStatus;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\Enum\StorageClass;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class HeadObjectOutput extends Result
{
    private $DeleteMarker;

    private $AcceptRanges;

    private $Expiration;

    private $Restore;

    private $LastModified;

    private $ContentLength;

    private $ETag;

    private $MissingMeta;

    private $VersionId;

    private $CacheControl;

    private $ContentDisposition;

    private $ContentEncoding;

    private $ContentLanguage;

    private $ContentType;

    private $Expires;

    private $WebsiteRedirectLocation;

    private $ServerSideEncryption;

    private $Metadata = [];

    private $SSECustomerAlgorithm;

    private $SSECustomerKeyMD5;

    private $SSEKMSKeyId;

    private $StorageClass;

    private $RequestCharged;

    private $ReplicationStatus;

    private $PartsCount;

    private $ObjectLockMode;

    private $ObjectLockRetainUntilDate;

    private $ObjectLockLegalHoldStatus;

    /**
     * Indicates that a range of bytes was specified.
     */
    public function getAcceptRanges(): ?string
    {
        $this->initialize();

        return $this->AcceptRanges;
    }

    /**
     * Specifies caching behavior along the request/reply chain.
     */
    public function getCacheControl(): ?string
    {
        $this->initialize();

        return $this->CacheControl;
    }

    /**
     * Specifies presentational information for the object.
     */
    public function getContentDisposition(): ?string
    {
        $this->initialize();

        return $this->ContentDisposition;
    }

    /**
     * Specifies what content encodings have been applied to the object and thus what decoding mechanisms must be applied to
     * obtain the media-type referenced by the Content-Type header field.
     */
    public function getContentEncoding(): ?string
    {
        $this->initialize();

        return $this->ContentEncoding;
    }

    /**
     * The language the content is in.
     */
    public function getContentLanguage(): ?string
    {
        $this->initialize();

        return $this->ContentLanguage;
    }

    /**
     * Size of the body in bytes.
     */
    public function getContentLength(): ?string
    {
        $this->initialize();

        return $this->ContentLength;
    }

    /**
     * A standard MIME type describing the format of the object data.
     */
    public function getContentType(): ?string
    {
        $this->initialize();

        return $this->ContentType;
    }

    /**
     * Specifies whether the object retrieved was (true) or was not (false) a Delete Marker. If false, this response header
     * does not appear in the response.
     */
    public function getDeleteMarker(): ?bool
    {
        $this->initialize();

        return $this->DeleteMarker;
    }

    /**
     * An ETag is an opaque identifier assigned by a web server to a specific version of a resource found at a URL.
     */
    public function getETag(): ?string
    {
        $this->initialize();

        return $this->ETag;
    }

    /**
     * If the object expiration is configured (see PUT Bucket lifecycle), the response includes this header. It includes the
     * expiry-date and rule-id key-value pairs providing object expiration information. The value of the rule-id is URL
     * encoded.
     */
    public function getExpiration(): ?string
    {
        $this->initialize();

        return $this->Expiration;
    }

    /**
     * The date and time at which the object is no longer cacheable.
     */
    public function getExpires(): ?\DateTimeInterface
    {
        $this->initialize();

        return $this->Expires;
    }

    /**
     * Last modified date of the object.
     */
    public function getLastModified(): ?\DateTimeInterface
    {
        $this->initialize();

        return $this->LastModified;
    }

    /**
     * A map of metadata to store with the object in S3.
     *
     * @return string[]
     */
    public function getMetadata(): array
    {
        $this->initialize();

        return $this->Metadata;
    }

    /**
     * This is set to the number of metadata entries not returned in `x-amz-meta` headers. This can happen if you create
     * metadata using an API like SOAP that supports more flexible metadata than the REST API. For example, using SOAP, you
     * can create metadata whose values are not legal HTTP headers.
     */
    public function getMissingMeta(): ?int
    {
        $this->initialize();

        return $this->MissingMeta;
    }

    /**
     * Specifies whether a legal hold is in effect for this object. This header is only returned if the requester has the
     * `s3:GetObjectLegalHold` permission. This header is not returned if the specified version of this object has never had
     * a legal hold applied. For more information about S3 Object Lock, see Object Lock.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/object-lock.html
     *
     * @return ObjectLockLegalHoldStatus::ON|ObjectLockLegalHoldStatus::OFF|null
     */
    public function getObjectLockLegalHoldStatus(): ?string
    {
        $this->initialize();

        return $this->ObjectLockLegalHoldStatus;
    }

    /**
     * The Object Lock mode, if any, that's in effect for this object. This header is only returned if the requester has the
     * `s3:GetObjectRetention` permission. For more information about S3 Object Lock, see Object Lock.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/object-lock.html
     *
     * @return ObjectLockMode::GOVERNANCE|ObjectLockMode::COMPLIANCE|null
     */
    public function getObjectLockMode(): ?string
    {
        $this->initialize();

        return $this->ObjectLockMode;
    }

    /**
     * The date and time when the Object Lock retention period expires. This header is only returned if the requester has
     * the `s3:GetObjectRetention` permission.
     */
    public function getObjectLockRetainUntilDate(): ?\DateTimeInterface
    {
        $this->initialize();

        return $this->ObjectLockRetainUntilDate;
    }

    /**
     * The count of parts this object has.
     */
    public function getPartsCount(): ?int
    {
        $this->initialize();

        return $this->PartsCount;
    }

    /**
     * Amazon S3 can return this header if your request involves a bucket that is either a source or destination in a
     * replication rule.
     *
     * @return ReplicationStatus::COMPLETE|ReplicationStatus::PENDING|ReplicationStatus::FAILED|ReplicationStatus::REPLICA|null
     */
    public function getReplicationStatus(): ?string
    {
        $this->initialize();

        return $this->ReplicationStatus;
    }

    /**
     * @return RequestCharged::REQUESTER|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    /**
     * If the object is an archived object (an object whose storage class is GLACIER), the response includes this header if
     * either the archive restoration is in progress (see RestoreObject or an archive copy is already restored.
     */
    public function getRestore(): ?string
    {
        $this->initialize();

        return $this->Restore;
    }

    /**
     * If server-side encryption with a customer-provided encryption key was requested, the response will include this
     * header confirming the encryption algorithm used.
     */
    public function getSSECustomerAlgorithm(): ?string
    {
        $this->initialize();

        return $this->SSECustomerAlgorithm;
    }

    /**
     * If server-side encryption with a customer-provided encryption key was requested, the response will include this
     * header to provide round-trip message integrity verification of the customer-provided encryption key.
     */
    public function getSSECustomerKeyMD5(): ?string
    {
        $this->initialize();

        return $this->SSECustomerKeyMD5;
    }

    /**
     * If present, specifies the ID of the AWS Key Management Service (AWS KMS) symmetric customer managed customer master
     * key (CMK) that was used for the object.
     */
    public function getSSEKMSKeyId(): ?string
    {
        $this->initialize();

        return $this->SSEKMSKeyId;
    }

    /**
     * If the object is stored using server-side encryption either with an AWS KMS customer master key (CMK) or an Amazon
     * S3-managed encryption key, the response includes this header with the value of the server-side encryption algorithm
     * used when storing this object in Amazon S3 (for example, AES256, aws:kms).
     *
     * @return ServerSideEncryption::AES256|ServerSideEncryption::AWS_KMS|null
     */
    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->ServerSideEncryption;
    }

    /**
     * Provides storage class information of the object. Amazon S3 returns this header for all objects except for Standard
     * storage class objects.
     *
     * @return StorageClass::STANDARD|StorageClass::REDUCED_REDUNDANCY|StorageClass::STANDARD_IA|StorageClass::ONEZONE_IA|StorageClass::INTELLIGENT_TIERING|StorageClass::GLACIER|StorageClass::DEEP_ARCHIVE|null
     */
    public function getStorageClass(): ?string
    {
        $this->initialize();

        return $this->StorageClass;
    }

    /**
     * Version of the object.
     */
    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    /**
     * If the bucket is configured as a website, redirects requests for this object to another object in the same bucket or
     * to an external URL. Amazon S3 stores the value of this header in the object metadata.
     */
    public function getWebsiteRedirectLocation(): ?string
    {
        $this->initialize();

        return $this->WebsiteRedirectLocation;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

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
        $this->ObjectLockMode = $headers['x-amz-object-lock-mode'][0] ?? null;
        $this->ObjectLockRetainUntilDate = isset($headers['x-amz-object-lock-retain-until-date'][0]) ? new \DateTimeImmutable($headers['x-amz-object-lock-retain-until-date'][0]) : null;
        $this->ObjectLockLegalHoldStatus = $headers['x-amz-object-lock-legal-hold'][0] ?? null;

        $this->Metadata = [];
        foreach ($headers as $name => $value) {
            if ('x-amz-meta-' === substr($name, 0, 11)) {
                $this->Metadata[$name] = $value[0];
            }
        }
    }
}
