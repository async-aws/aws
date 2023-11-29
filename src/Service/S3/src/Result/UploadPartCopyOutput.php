<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\ValueObject\CopyPartResult;

class UploadPartCopyOutput extends Result
{
    /**
     * The version of the source object that was copied, if you have enabled versioning on the source bucket.
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceVersionId;

    /**
     * Container for all response elements.
     *
     * @var CopyPartResult|null
     */
    private $copyPartResult;

    /**
     * The server-side encryption algorithm used when you store this object in Amazon S3 (for example, `AES256`, `aws:kms`).
     *
     * > For directory buckets, only server-side encryption with Amazon S3 managed keys (SSE-S3) (`AES256`) is supported.
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
     * If present, indicates the ID of the Key Management Service (KMS) symmetric encryption customer managed key that was
     * used for the object.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $sseKmsKeyId;

    /**
     * Indicates whether the multipart upload uses an S3 Bucket Key for server-side encryption with Key Management Service
     * (KMS) keys (SSE-KMS).
     *
     * > This functionality is not supported for directory buckets.
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

    public function getCopyPartResult(): ?CopyPartResult
    {
        $this->initialize();

        return $this->copyPartResult;
    }

    public function getCopySourceVersionId(): ?string
    {
        $this->initialize();

        return $this->copySourceVersionId;
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

        $this->copySourceVersionId = $headers['x-amz-copy-source-version-id'][0] ?? null;
        $this->serverSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->sseCustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->sseCustomerKeyMd5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->sseKmsKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->bucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->copyPartResult = new CopyPartResult([
            'ETag' => ($v = $data->ETag) ? (string) $v : null,
            'LastModified' => ($v = $data->LastModified) ? new \DateTimeImmutable((string) $v) : null,
            'ChecksumCRC32' => ($v = $data->ChecksumCRC32) ? (string) $v : null,
            'ChecksumCRC32C' => ($v = $data->ChecksumCRC32C) ? (string) $v : null,
            'ChecksumSHA1' => ($v = $data->ChecksumSHA1) ? (string) $v : null,
            'ChecksumSHA256' => ($v = $data->ChecksumSHA256) ? (string) $v : null,
        ]);
    }
}
