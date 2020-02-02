<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PutObjectOutput extends Result
{
    private $Expiration;
    private $ETag;
    private $ServerSideEncryption;
    private $VersionId;
    private $SSECustomerAlgorithm;
    private $SSECustomerKeyMD5;
    private $SSEKMSKeyId;
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

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->Expiration = $headers['x-amz-expiration'];
        $this->ETag = $headers['ETag'];
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'];
        $this->VersionId = $headers['x-amz-version-id'];
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'];
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-MD5'];
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'];
        $this->SSEKMSEncryptionContext = $headers['x-amz-server-side-encryption-context'];
        $this->RequestCharged = $headers['x-amz-request-charged'];
    }
}
