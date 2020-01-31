<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class CopyObjectOutput extends Result
{
    use CopyObjectOutputTrait;

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

    public function getCopyObjectResult(): ?CopyObjectResult
    {
        $this->initialize();

        return $this->CopyObjectResult;
    }

    public function getExpiration(): ?string
    {
        $this->initialize();

        return $this->Expiration;
    }

    public function getCopySourceVersionId(): ?string
    {
        $this->initialize();

        return $this->CopySourceVersionId;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->ServerSideEncryption;
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

    public function getSSEKMSKeyId(): ?string
    {
        $this->initialize();

        return $this->SSEKMSKeyId;
    }

    public function getSSEKMSEncryptionContext(): ?string
    {
        $this->initialize();

        return $this->SSEKMSEncryptionContext;
    }

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }
}
