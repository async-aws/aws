<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;

class CreateMultipartUploadOutput extends Result
{
    /**
     * If the bucket has a lifecycle rule configured with an action to abort incomplete multipart uploads and the prefix in
     * the lifecycle rule matches the object name in the request, the response includes this header. The header indicates
     * when the initiated multipart upload becomes eligible for an abort operation. For more information, see  Aborting
     * Incomplete Multipart Uploads Using a Bucket Lifecycle Policy.
     *
     * @see https://docs.aws.amazon.com/AmazonS3/latest/dev/mpuoverview.html#mpu-abort-incomplete-mpu-lifecycle-config
     */
    private $AbortDate;

    /**
     * This header is returned along with the `x-amz-abort-date` header. It identifies the applicable lifecycle
     * configuration rule that defines the action to abort incomplete multipart uploads.
     */
    private $AbortRuleId;

    /**
     * Name of the bucket to which the multipart upload was initiated.
     */
    private $Bucket;

    /**
     * Object key for which the multipart upload was initiated.
     */
    private $Key;

    /**
     * ID for the initiated multipart upload.
     */
    private $UploadId;

    /**
     * The server-side encryption algorithm used when storing this object in Amazon S3 (for example, AES256, aws:kms).
     */
    private $ServerSideEncryption;

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
     * If present, specifies the AWS KMS Encryption Context to use for object encryption. The value of this header is a
     * base64-encoded UTF-8 string holding JSON with the encryption context key-value pairs.
     */
    private $SSEKMSEncryptionContext;

    private $RequestCharged;

    public function getAbortDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->AbortDate;
    }

    public function getAbortRuleId(): ?string
    {
        $this->initialize();

        return $this->AbortRuleId;
    }

    public function getBucket(): ?string
    {
        $this->initialize();

        return $this->Bucket;
    }

    public function getKey(): ?string
    {
        $this->initialize();

        return $this->Key;
    }

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
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

    public function getSSEKMSEncryptionContext(): ?string
    {
        $this->initialize();

        return $this->SSEKMSEncryptionContext;
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

    public function getUploadId(): ?string
    {
        $this->initialize();

        return $this->UploadId;
    }

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->AbortDate = isset($headers['x-amz-abort-date'][0]) ? new \DateTimeImmutable($headers['x-amz-abort-date'][0]) : null;
        $this->AbortRuleId = $headers['x-amz-abort-rule-id'][0] ?? null;
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->SSEKMSEncryptionContext = $headers['x-amz-server-side-encryption-context'][0] ?? null;
        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent());
        $this->Bucket = ($v = $data->Bucket) ? (string) $v : null;
        $this->Key = ($v = $data->Key) ? (string) $v : null;
        $this->UploadId = ($v = $data->UploadId) ? (string) $v : null;
    }
}
