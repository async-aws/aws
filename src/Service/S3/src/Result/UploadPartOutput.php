<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;

class UploadPartOutput extends Result
{
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
     * Entity tag for the uploaded object.
     *
     * @var string|null
     */
    private $etag;

    /**
     * The Base64 encoded, 32-bit `CRC32` checksum of the part. This will only be present if the checksum was provided in
     * the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32;

    /**
     * The Base64 encoded, 32-bit `CRC32C` checksum of the part. This will only be present if the checksum was provided in
     * the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc32C;

    /**
     * The Base64 encoded, 64-bit `CRC64NVME` checksum of the part. This will only be present if the checksum was provided
     * in the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumCrc64Nvme;

    /**
     * The Base64 encoded, 160-bit `SHA1` checksum of the part. This will only be present if the checksum was provided in
     * the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha1;

    /**
     * The Base64 encoded, 256-bit `SHA256` checksum of the part. This will only be present if the checksum was provided in
     * the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha256;

    /**
     * The Base64 encoded, 512-bit `SHA512` checksum of the part. This will only be present if the checksum was provided in
     * the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumSha512;

    /**
     * The Base64 encoded, 128-bit `MD5` checksum of the part. This will only be present if the checksum was provided in the
     * request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumMd5;

    /**
     * The Base64 encoded, 64-bit `XXHASH64` checksum of the part. This will only be present if the checksum was provided in
     * the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumXxhash64;

    /**
     * The Base64 encoded, 64-bit `XXHASH3` checksum of the part. This will only be present if the checksum was provided in
     * the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumXxhash3;

    /**
     * The Base64 encoded, 128-bit `XXHASH128` checksum of the part. This will only be present if the checksum was provided
     * in the request. For more information, see Checking object integrity [^1] in the *Amazon S3 User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/userguide/checking-object-integrity.html
     *
     * @var string|null
     */
    private $checksumXxhash128;

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

    public function getChecksumMd5(): ?string
    {
        $this->initialize();

        return $this->checksumMd5;
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

    public function getChecksumSha512(): ?string
    {
        $this->initialize();

        return $this->checksumSha512;
    }

    public function getChecksumXxhash128(): ?string
    {
        $this->initialize();

        return $this->checksumXxhash128;
    }

    public function getChecksumXxhash3(): ?string
    {
        $this->initialize();

        return $this->checksumXxhash3;
    }

    public function getChecksumXxhash64(): ?string
    {
        $this->initialize();

        return $this->checksumXxhash64;
    }

    public function getEtag(): ?string
    {
        $this->initialize();

        return $this->etag;
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

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->serverSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->etag = $headers['etag'][0] ?? null;
        $this->checksumCrc32 = $headers['x-amz-checksum-crc32'][0] ?? null;
        $this->checksumCrc32C = $headers['x-amz-checksum-crc32c'][0] ?? null;
        $this->checksumCrc64Nvme = $headers['x-amz-checksum-crc64nvme'][0] ?? null;
        $this->checksumSha1 = $headers['x-amz-checksum-sha1'][0] ?? null;
        $this->checksumSha256 = $headers['x-amz-checksum-sha256'][0] ?? null;
        $this->checksumSha512 = $headers['x-amz-checksum-sha512'][0] ?? null;
        $this->checksumMd5 = $headers['x-amz-checksum-md5'][0] ?? null;
        $this->checksumXxhash64 = $headers['x-amz-checksum-xxhash64'][0] ?? null;
        $this->checksumXxhash3 = $headers['x-amz-checksum-xxhash3'][0] ?? null;
        $this->checksumXxhash128 = $headers['x-amz-checksum-xxhash128'][0] ?? null;
        $this->sseCustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->sseCustomerKeyMd5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->sseKmsKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->bucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;
    }
}
