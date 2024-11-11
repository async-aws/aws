<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;
use AsyncAws\S3\ValueObject\CopyObjectResult;

class CopyObjectOutput extends Result
{
    /**
     * Container for all response elements.
     *
     * @var CopyObjectResult|null
     */
    private $copyObjectResult;

    /**
     * If the object expiration is configured, the response includes this header.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $expiration;

    /**
     * Version ID of the source object that was copied.
     *
     * > This functionality is not supported when the source object is in a directory bucket.
     *
     * @var string|null
     */
    private $copySourceVersionId;

    /**
     * Version ID of the newly created copy.
     *
     * > This functionality is not supported for directory buckets.
     *
     * @var string|null
     */
    private $versionId;

    /**
     * The server-side encryption algorithm used when you store this object in Amazon S3 (for example, `AES256`, `aws:kms`,
     * `aws:kms:dsse`).
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
     * header is a base64-encoded UTF-8 string holding JSON with the encryption context key-value pairs.
     *
     * @var string|null
     */
    private $sseKmsEncryptionContext;

    /**
     * Indicates whether the copied object uses an S3 Bucket Key for server-side encryption with Key Management Service
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

    public function getCopyObjectResult(): ?CopyObjectResult
    {
        $this->initialize();

        return $this->copyObjectResult;
    }

    public function getCopySourceVersionId(): ?string
    {
        $this->initialize();

        return $this->copySourceVersionId;
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
        $this->copySourceVersionId = $headers['x-amz-copy-source-version-id'][0] ?? null;
        $this->versionId = $headers['x-amz-version-id'][0] ?? null;
        $this->serverSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->sseCustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->sseCustomerKeyMd5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->sseKmsKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->sseKmsEncryptionContext = $headers['x-amz-server-side-encryption-context'][0] ?? null;
        $this->bucketKeyEnabled = isset($headers['x-amz-server-side-encryption-bucket-key-enabled'][0]) ? filter_var($headers['x-amz-server-side-encryption-bucket-key-enabled'][0], \FILTER_VALIDATE_BOOLEAN) : null;
        $this->requestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->copyObjectResult = $this->populateResultCopyObjectResult($data);
    }

    private function populateResultCopyObjectResult(\SimpleXMLElement $xml): CopyObjectResult
    {
        return new CopyObjectResult([
            'ETag' => (null !== $v = $xml->ETag[0]) ? (string) $v : null,
            'LastModified' => (null !== $v = $xml->LastModified[0]) ? new \DateTimeImmutable((string) $v) : null,
            'ChecksumCRC32' => (null !== $v = $xml->ChecksumCRC32[0]) ? (string) $v : null,
            'ChecksumCRC32C' => (null !== $v = $xml->ChecksumCRC32C[0]) ? (string) $v : null,
            'ChecksumSHA1' => (null !== $v = $xml->ChecksumSHA1[0]) ? (string) $v : null,
            'ChecksumSHA256' => (null !== $v = $xml->ChecksumSHA256[0]) ? (string) $v : null,
        ]);
    }
}
