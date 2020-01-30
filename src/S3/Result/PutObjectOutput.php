<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Aws\Result;

class PutObjectOutput extends Result
{
    use PutObjectOutputTrait;

    private $Expiration;

    private $ETag;

    private $ServerSideEncryption;

    private $VersionId;

    private $SSECustomerAlgorithm;

    private $SSECustomerKeyMD5;

    private $SSEKMSKeyId;

    private $SSEKMSEncryptionContext;

    private $RequestCharged;

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
