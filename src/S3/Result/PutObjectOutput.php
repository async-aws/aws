<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

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

    public function getExpiration(): ?string
    {
        $this->resolve();

        return $this->Expiration;
    }

    public function getETag(): ?string
    {
        $this->resolve();

        return $this->ETag;
    }

    public function getServerSideEncryption(): ?string
    {
        $this->resolve();

        return $this->ServerSideEncryption;
    }

    public function getVersionId(): ?string
    {
        $this->resolve();

        return $this->VersionId;
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
