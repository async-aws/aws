<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Aws\Result;
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

    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->Expiration = $data->Expiration;
        $this->ETag = $data->ETag;
        $this->ServerSideEncryption = $data->ServerSideEncryption;
        $this->VersionId = $data->VersionId;
        $this->SSECustomerAlgorithm = $data->SSECustomerAlgorithm;
        $this->SSECustomerKeyMD5 = $data->SSECustomerKeyMD5;
        $this->SSEKMSKeyId = $data->SSEKMSKeyId;
        $this->SSEKMSEncryptionContext = $data->SSEKMSEncryptionContext;
        $this->RequestCharged = $data->RequestCharged;
    }

    public function getExpiration(): string
    {
        $this->initialize();

        return $this->Expiration;
    }

    public function getETag(): string
    {
        $this->initialize();

        return $this->ETag;
    }

    public function getServerSideEncryption(): string
    {
        $this->initialize();

        return $this->ServerSideEncryption;
    }

    public function getVersionId(): string
    {
        $this->initialize();

        return $this->VersionId;
    }

    public function getSSECustomerAlgorithm(): string
    {
        $this->initialize();

        return $this->SSECustomerAlgorithm;
    }

    public function getSSECustomerKeyMD5(): string
    {
        $this->initialize();

        return $this->SSECustomerKeyMD5;
    }

    public function getSSEKMSKeyId(): string
    {
        $this->initialize();

        return $this->SSEKMSKeyId;
    }

    public function getSSEKMSEncryptionContext(): string
    {
        $this->initialize();

        return $this->SSEKMSEncryptionContext;
    }

    public function getRequestCharged(): string
    {
        $this->initialize();

        return $this->RequestCharged;
    }
}
