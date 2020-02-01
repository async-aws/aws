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
        $this->resolve();

        return $this->CopyObjectResult;
    }

    public function getExpiration(): ?string
    {
        $this->resolve();

        return $this->Expiration;
    }

    public function getCopySourceVersionId(): ?string
    {
        $this->resolve();

        return $this->CopySourceVersionId;
    }

    public function getVersionId(): ?string
    {
        $this->resolve();

        return $this->VersionId;
    }

    public function getServerSideEncryption(): ?string
    {
        $this->resolve();

        return $this->ServerSideEncryption;
    }

    public function getSSECustomerAlgorithm(): ?string
    {
        $this->resolve();

        return $this->SSECustomerAlgorithm;
    }

    public function getSSECustomerKeyMD5(): ?string
    {
        $this->resolve();

        return $this->SSECustomerKeyMD5;
    }

    public function getSSEKMSKeyId(): ?string
    {
        $this->resolve();

        return $this->SSEKMSKeyId;
    }

    public function getSSEKMSEncryptionContext(): ?string
    {
        $this->resolve();

        return $this->SSEKMSEncryptionContext;
    }

    public function getRequestCharged(): ?string
    {
        $this->resolve();

        return $this->RequestCharged;
    }
}
