<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use AsyncAws\S3\Enum\ServerSideEncryption;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CopyObjectOutput extends Result
{
    private $CopyObjectResult;

    private $Expiration;

    private $CopySourceVersionId;

    private $VersionId;

    private $ServerSideEncryption;

    private $SSECustomerAlgorithm;

    private $SSECustomerKeyMD5;

    private $SSEKMSKeyId;

    private $SSEKMSEncryptionContext;

    private $RequestCharged;

    /**
     * Container for all response elements.
     */
    public function getCopyObjectResult(): ?CopyObjectResult
    {
        $this->initialize();

        return $this->CopyObjectResult;
    }

    /**
     * Version of the copied object in the destination bucket.
     */
    public function getCopySourceVersionId(): ?string
    {
        $this->initialize();

        return $this->CopySourceVersionId;
    }

    /**
     * If the object expiration is configured, the response includes this header.
     */
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
     * If present, specifies the AWS KMS Encryption Context to use for object encryption. The value of this header is a
     * base64-encoded UTF-8 string holding JSON with the encryption context key-value pairs.
     */
    public function getSSEKMSEncryptionContext(): ?string
    {
        $this->initialize();

        return $this->SSEKMSEncryptionContext;
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
     * The server-side encryption algorithm used when storing this object in Amazon S3 (for example, AES256, aws:kms).
     *
     * @return ServerSideEncryption::*|null
     */
    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->ServerSideEncryption;
    }

    /**
     * Version ID of the newly created copy.
     */
    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->Expiration = $headers['x-amz-expiration'][0] ?? null;
        $this->CopySourceVersionId = $headers['x-amz-copy-source-version-id'][0] ?? null;
        $this->VersionId = $headers['x-amz-version-id'][0] ?? null;
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'][0] ?? null;
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'][0] ?? null;
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-md5'][0] ?? null;
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'][0] ?? null;
        $this->SSEKMSEncryptionContext = $headers['x-amz-server-side-encryption-context'][0] ?? null;
        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;

        $data = new \SimpleXMLElement($response->getContent(false));
        $this->CopyObjectResult = !$data ? null : new CopyObjectResult([
            'ETag' => ($v = $data->ETag) ? (string) $v : null,
            'LastModified' => ($v = $data->LastModified) ? new \DateTimeImmutable((string) $v) : null,
        ]);
    }
}
