<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;

class PutObjectOutput extends Result
{
    /**
     * If the expiration is configured for the object (see PutBucketLifecycleConfiguration [^1]) in the *Amazon S3 User
     * Guide*, the response includes this header. It includes the `expiry-date` and `rule-id` key-value pairs that provide
     * information about object expiration. The value of the `rule-id` is URL-encoded.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_PutBucketLifecycleConfiguration.html
     *
     * @var string|null
     */
    private $expiration;

    /**
     * Entity tag for the uploaded object.
     *
     * **General purpose buckets ** - To ensure that data is not corrupted traversing the network, for objects where the
     * ETag is the MD5 digest of the object, you can calculate the MD5 while putting an object to Amazon S3 and compare the
     * returned ETag to the calculated MD5 value.
     *
     * **Directory buckets ** - The ETag for the object in a directory bucket isn't the MD5 digest of the object.
     *
     * @var string|null
     */
    private $etag;

    /**
     * The base64-encoded, 32-bit CRC-32 checksum of the object. This will only be present if it was uploaded with the
     * object. When you use an API operation on an object that was uploaded using multipart uploads, this value may not be a
     * direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual
     * part. For more information about how checksums are calculated with multipart uploads, see Checking object integrity
     * [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * The base64-encoded, 32-bit CRC-32C checksum of the object. This will only be present if it was uploaded with the
     * object. When you use an API operation on an object that was uploaded using multipart uploads, this value may not be a
     * direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual
     * part. For more information about how checksums are calculated with multipart uploads, see Checking object integrity
     * [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * The base64-encoded, 160-bit SHA-1 digest of the object. This will only be present if it was uploaded with the object.
     * When you use the API operation on an object that was uploaded using multipart uploads, this value may not be a direct
     * checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual part.
     * For more information about how checksums are calculated with multipart uploads, see Checking object integrity [^1] in
     * the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * The base64-encoded, 256-bit SHA-256 digest of the object. This will only be present if it was uploaded with the
     * object. When you use an API operation on an object that was uploaded using multipart uploads, this value may not be a
     * direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each individual
     * part. For more information about how checksums are calculated with multipart uploads, see Checking object integrity
     * [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * The server-side encryption algorithm used when you store this object in Amazon S3.
     *
     * @var ServerSideEncryption::*|null
     */
    private $serverSideEncryption;

    /**
     * Version ID of the object.
     *
     * If you enable versioning for a bucket, Amazon S3 automatically generates a unique version ID for the object being
     * stored. Amazon S3 returns this ID in the response. When you enable versioning for a bucket, if Amazon S3 receives
     * multiple write requests for the same object simultaneously, it stores all of the objects. For more information about
     * versioning, see Adding Objects to Versioning-Enabled Buckets [^1] in the *Amazon S3 User Guide*. For information
     * about returning the versioning state of a bucket, see GetBucketVersioning [^2].
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/AddingObjectstoVersioningEnabledBuckets.html
     * [^2]: https://docs.aws.amazon.com/AmazonS3/latest/API/API_GetBucketVersioning.html
     *
     * @var string|null
     */
    private $versionId;

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
     * If present, indicates the Amazon Web Services KMS Encryption Context to use for object encryption. The value of this
     * header is a Base64-encoded string of a UTF-8 encoded JSON, which contains the encryption context as key-value pairs.
     * This value is stored as object metadata and automatically gets passed on to Amazon Web Services KMS for future
     * `GetObject` operations on this object.
     *
     * @var string|null
     */
    private $sseKmsEncryptionContext;

    /**
     * Indicates whether the uploaded object uses an S3 Bucket Key for server-side encryption with Key Management Service
     * (KMS) keys (SSE-KMS).
     *
     * @var bool|null
     */
    private $bucketKeyEnabled;

    /**
     * The size of the object in bytes. This will only be present if you append to an object.
     *
     * > This functionality is only supported for objects in the Amazon S3 Express One Zone storage class in directory
     * > buckets.
     *
     * @var int|null
     */
    private $size;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    public function getBucketKeyEnabled(): ?bool
    {
        $this->initialize();

        return $this->bucketKeyEnabled;
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

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->requestCharged;
    }

    /**
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->serverSideEncryption;
    }

    public function getSize(): ?int
    {
        $this->initialize();

        return $this->size;
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

    public function getSseKmsEncryptionContext(): ?string
    {
        $this->initialize();

        return $this->sseKmsEncryptionContext;
    }

    public function getSseKmsKeyId(): ?string
    {
        $this->initialize();

        return $this->sseKmsKeyId;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->versionId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->expiration = $headers['x-amz-expiration'][0] ?? null;
        $this->etag = $headers['etag'][0] ?? null;
        $this->checksumCrc32 = $headers['x-amz-checksum-crc32'][0] ?? null;
        $this->checksumCrc32C = $headers['x-amz-checksum-crc32c'][0] ?? null;
        $this->checksumSha1 = $headers['x-amz-checksum-sha1'][0] ?? null;
        $this->checksumSha256 = $headers['x-amz-checksum-sha256'][0] ?? null;
        $this->serverSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->versionId = $headers['x-amz-version-id'][0] ?? null;
        $this->sseCustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->sseCustomerKeyMd5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->sseKmsKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->sseKmsEncryptionContext = $headers['x-amz-server-side-encryption-context'][0] ?? null;
        $this->bucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->size = isset($headers['x-amz-object-size'][0]) ? (int) $headers['x-amz-object-size'][0] : null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;
    }
}
