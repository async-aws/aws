<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\ChecksumAlgorithm;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;

class CreateMultipartUploadOutput extends Result
{
    /**
     * If the bucket has a lifecycle rule configured with an action to abort incomplete multipart uploads and the prefix in
     * the lifecycle rule matches the object name in the request, the response includes this header. The header indicates
     * when the initiated multipart upload becomes eligible for an abort operation. For more information, see Aborting
     * Incomplete Multipart Uploads Using a Bucket Lifecycle Configuration [^1] in the *Amazon S3 User Guide*.
     *
     * The response also includes the `x-amz-abort-rule-id` header that provides the ID of the lifecycle configuration rule
     * that defines the abort action.
     *
     * > This functionality is not supported for directory buckets.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html#mpu-abort-incomplete-mpu-lifecycle-config
     *
     * @var \DateTimeImmutable|null
     */
    private $abortDate;

    /**
     * This header is returned along with the `x-amz-abort-date` header. It identifies the applicable lifecycle
     * configuration rule that defines the action to abort incomplete multipart uploads.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $abortRuleId;

    /**
     * The name of the bucket to which the multipart upload was initiated. Does not return the access point ARN or access
     * point alias if used.
     *
     * > Access points are not supported by directory buckets.
     *
     * @var string|null
     */
    private $bucket;

    /**
     * Object key for which the multipart upload was initiated.
     *
     * @var string|null
     */
    private $key;

    /**
     * ID for the initiated multipart upload.
     *
     * @var string|null
     */
    private $uploadId;

    /**
     * The server-side encryption algorithm used when you store this object in Amazon S3 (for example, `AES256`, `aws:kms`).
     *
     * @var ServerSideEncryption::*|null
     */
    private $serverSideEncryption;

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
     *
     * @var string|null
     */
    private $sseKmsEncryptionContext;

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

    /**
     * The algorithm that was used to create a checksum of the object.
     *
     * @var ChecksumAlgorithm::*|null
     */
    private $checksumAlgorithm;

    public function getAbortDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->abortDate;
    }

    public function getAbortRuleId(): ?string
    {
        $this->initialize();

        return $this->abortRuleId;
    }

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

    /**
     * @return ChecksumAlgorithm::*|null
     */
    public function getChecksumAlgorithm(): ?string
    {
        $this->initialize();

        return $this->checksumAlgorithm;
    }

    public function getKey(): ?string
    {
        $this->initialize();

        return $this->key;
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

    public function getUploadId(): ?string
    {
        $this->initialize();

        return $this->uploadId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->abortDate = isset($headers['x-amz-abort-date'][0]) ? new \DateTimeImmutable($headers['x-amz-abort-date'][0]) : null;
        $this->abortRuleId = $headers['x-amz-abort-rule-id'][0] ?? null;
        $this->serverSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->sseCustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->sseCustomerKeyMd5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->sseKmsKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->sseKmsEncryptionContext = $headers['x-amz-server-side-encryption-context'][0] ?? null;
        $this->bucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;
        $this->checksumAlgorithm = $headers['x-amz-checksum-algorithm'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->bucket = (null !== $v = $data->Bucket[0]) ? (string) $v : null;
        $this->key = (null !== $v = $data->Key[0]) ? (string) $v : null;
        $this->uploadId = (null !== $v = $data->UploadId[0]) ? (string) $v : null;
    }
}
