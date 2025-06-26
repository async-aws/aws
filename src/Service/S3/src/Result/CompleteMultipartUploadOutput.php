<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\ChecksumType;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;

class CompleteMultipartUploadOutput extends Result
{
    /**
     * The URI that identifies the newly created object.
     *
     * @var string|null
     */
    private $location;

    /**
     * The name of the bucket that contains the newly created object. Does not return the access point ARN or access point
     * alias if used.
     *
     * > Access points are not supported by directory buckets.
     *
     * @var string|null
     */
    private $bucket;

    /**
     * The object key of the newly created object.
     *
     * @var string|null
     */
    private $key;

    /**
     * If the object expiration is configured, this will contain the expiration date (`expiry-date`) and rule ID
     * (`rule-id`). The value of `rule-id` is URL-encoded.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $expiration;

    /**
     * Entity tag that identifies the newly created object's data. Objects with different object data will have different
     * entity tags. The entity tag is an opaque string. The entity tag may or may not be an MD5 digest of the object data.
     * If the entity tag is not an MD5 digest of the object data, it will contain one or more nonhexadecimal characters
     * and/or will consist of less than 32 or more than 32 hexadecimal digits. For more information about how the entity tag
     * is calculated, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $etag;

    /**
     * The Base64 encoded, 32-bit `CRC32 checksum` of the object. This checksum is only be present if the checksum was
     * uploaded with the object. When you use an API operation on an object that was uploaded using multipart uploads, this
     * value may not be a direct checksum value of the full object. Instead, it's a calculation based on the checksum values
     * of each individual part. For more information about how checksums are calculated with multipart uploads, see Checking
     * object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * The Base64 encoded, 32-bit `CRC32C` checksum of the object. This checksum is only present if the checksum was
     * uploaded with the object. When you use an API operation on an object that was uploaded using multipart uploads, this
     * value may not be a direct checksum value of the full object. Instead, it's a calculation based on the checksum values
     * of each individual part. For more information about how checksums are calculated with multipart uploads, see Checking
     * object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * This header can be used as a data integrity check to verify that the data received is the same data that was
     * originally sent. This header specifies the Base64 encoded, 64-bit `CRC64NVME` checksum of the object. The `CRC64NVME`
     * checksum is always a full object checksum. For more information, see Checking object integrity in the Amazon S3 User
     * Guide [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc64Nvme;

    /**
     * The Base64 encoded, 160-bit `SHA1` digest of the object. This will only be present if the object was uploaded with
     * the object. When you use the API operation on an object that was uploaded using multipart uploads, this value may not
     * be a direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each
     * individual part. For more information about how checksums are calculated with multipart uploads, see Checking object
     * integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * The Base64 encoded, 256-bit `SHA256` digest of the object. This will only be present if the object was uploaded with
     * the object. When you use an API operation on an object that was uploaded using multipart uploads, this value may not
     * be a direct checksum value of the full object. Instead, it's a calculation based on the checksum values of each
     * individual part. For more information about how checksums are calculated with multipart uploads, see Checking object
     * integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html#large-object-checksums
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * The checksum type, which determines how part-level checksums are combined to create an object-level checksum for
     * multipart objects. You can use this header as a data integrity check to verify that the checksum type that is
     * received is the same checksum type that was specified during the `CreateMultipartUpload` request. For more
     * information, see Checking object integrity in the Amazon S3 User Guide [^1].
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var ChecksumType::*|null
     */
    private $checksumType;

    /**
     * The server-side encryption algorithm used when storing this object in Amazon S3.
     *
     * > When accessing data stored in Amazon FSx file systems using S3 access points, the only valid server side encryption
     * > option is `aws:fsx`.
     *
     * @var ServerSideEncryption::*|null
     */
    private $serverSideEncryption;

    /**
     * Version ID of the newly created object, in case the bucket has versioning turned on.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * If present, indicates the ID of the KMS key that was used for object encryption.
     *
     * @var string|null
     */
    private $sseKmsKeyId;

    /**
     * Indicates whether the multipart upload uses an S3 Bucket Key for server-side encryption with Key Management Service
     * (KMS) keys (SSE-KMS).
     *
     * @var bool|null
     */
    private $bucketKeyEnabled;

    /**
     * @var RequestCharged::*|null
     */
    private $requestCharged;

    public function getBucket(): ?string
    {
        $this->initialize();

        return $this->bucket;
    }

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

    public function getKey(): ?string
    {
        $this->initialize();

        return $this->key;
    }

    public function getLocation(): ?string
    {
        $this->initialize();

        return $this->location;
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
        $this->serverSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->versionId = $headers['x-amz-version-id'][0] ?? null;
        $this->sseKmsKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->bucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->location = (null !== $v = $data->Location[0]) ? (string) $v : null;
        $this->bucket = (null !== $v = $data->Bucket[0]) ? (string) $v : null;
        $this->key = (null !== $v = $data->Key[0]) ? (string) $v : null;
        $this->etag = (null !== $v = $data->ETag[0]) ? (string) $v : null;
        $this->checksumCrc32 = (null !== $v = $data->ChecksumCRC32[0]) ? (string) $v : null;
        $this->checksumCrc32C = (null !== $v = $data->ChecksumCRC32C[0]) ? (string) $v : null;
        $this->checksumCrc64Nvme = (null !== $v = $data->ChecksumCRC64NVME[0]) ? (string) $v : null;
        $this->checksumSha1 = (null !== $v = $data->ChecksumSHA1[0]) ? (string) $v : null;
        $this->checksumSha256 = (null !== $v = $data->ChecksumSHA256[0]) ? (string) $v : null;
        $this->checksumType = (null !== $v = $data->ChecksumType[0]) ? (string) $v : null;
    }
}
