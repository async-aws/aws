<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PutObjectOutput extends Result
{
    /**
     * If the expiration is configured for the object (see PutBucketLifecycleConfiguration), the response includes this
     * header. It includes the expiry-date and rule-id key-value pairs that provide information about object expiration. The
     * value of the rule-id is URL encoded.
     */
    private $Expiration;

    /**
     * Entity tag for the uploaded object.
     */
    private $ETag;

    /**
     * If you specified server-side encryption either with an AWS KMS customer master key (CMK) or Amazon S3-managed
     * encryption key in your PUT request, the response includes this header. It confirms the encryption algorithm that
     * Amazon S3 used to encrypt the object.
     */
    private $ServerSideEncryption;

    /**
     * Version of the object.
     */
    private $VersionId;

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
     * If `x-amz-server-side-encryption` is present and has the value of `aws:kms`, this header specifies the ID of the AWS
     * Key Management Service (AWS KMS) symmetric customer managed customer master key (CMK) that was used for the object.
     */
    private $SSEKMSKeyId;

    /**
     * If present, specifies the AWS KMS Encryption Context to use for object encryption. The value of this header is a
     * base64-encoded UTF-8 string holding JSON with the encryption context key-value pairs.
     */
    private $SSEKMSEncryptionContext;

    private $RequestCharged;

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

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->Expiration = $headers['x-amz-expiration'][0] ?? null;
        $this->ETag = $headers['etag'][0] ?? null;
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->VersionId = $headers['x-amz-version-id'][0] ?? null;
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->SSEKMSEncryptionContext = $headers['x-amz-server-side-encryption-context'][0] ?? null;
        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;
    }
}
