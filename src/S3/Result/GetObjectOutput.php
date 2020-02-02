<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;

class GetObjectOutput extends Result
{
    use GetObjectOutputTrait;

    private $Body;

    private $DeleteMarker;

    private $AcceptRanges;

    private $Expiration;

    private $Restore;

    private $LastModified;

    private $ContentLength;

    private $ETag;

    private $MissingMeta;

    private $VersionId;

    private $CacheControl;

    private $ContentDisposition;

    private $ContentEncoding;

    private $ContentLanguage;

    private $ContentRange;

    private $ContentType;

    private $Expires;

    private $WebsiteRedirectLocation;

    private $ServerSideEncryption;

    private $Metadata;

    private $SSECustomerAlgorithm;

    private $SSECustomerKeyMD5;

    private $SSEKMSKeyId;

    private $StorageClass;

    private $RequestCharged;

    private $ReplicationStatus;

    private $PartsCount;

    private $TagCount;

    private $ObjectLockMode;

    private $ObjectLockRetainUntilDate;

    private $ObjectLockLegalHoldStatus;

    public function getBody(): ?string
    {
        $this->initialize();

        return $this->Body;
    }

    public function getDeleteMarker(): ?bool
    {
        $this->initialize();

        return $this->DeleteMarker;
    }

    public function getAcceptRanges(): ?string
    {
        $this->initialize();

        return $this->AcceptRanges;
    }

    public function getExpiration(): ?string
    {
        $this->initialize();

        return $this->Expiration;
    }

    public function getRestore(): ?string
    {
        $this->initialize();

        return $this->Restore;
    }

    public function getLastModified(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->LastModified;
    }

    public function getContentLength(): ?string
    {
        $this->initialize();

        return $this->ContentLength;
    }

    public function getETag(): ?string
    {
        $this->initialize();

        return $this->ETag;
    }

    public function getMissingMeta(): ?int
    {
        $this->initialize();

        return $this->MissingMeta;
    }

    public function getVersionId(): ?string
    {
        $this->initialize();

        return $this->VersionId;
    }

    public function getCacheControl(): ?string
    {
        $this->initialize();

        return $this->CacheControl;
    }

    public function getContentDisposition(): ?string
    {
        $this->initialize();

        return $this->ContentDisposition;
    }

    public function getContentEncoding(): ?string
    {
        $this->initialize();

        return $this->ContentEncoding;
    }

    public function getContentLanguage(): ?string
    {
        $this->initialize();

        return $this->ContentLanguage;
    }

    public function getContentRange(): ?string
    {
        $this->initialize();

        return $this->ContentRange;
    }

    public function getContentType(): ?string
    {
        $this->initialize();

        return $this->ContentType;
    }

    public function getExpires(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->Expires;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        $this->initialize();

        return $this->WebsiteRedirectLocation;
    }

    public function getServerSideEncryption(): ?string
    {
        $this->initialize();

        return $this->ServerSideEncryption;
    }

    public function getMetadata(): ?array
    {
        $this->initialize();

        return $this->Metadata;
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

    public function getStorageClass(): ?string
    {
        $this->initialize();

        return $this->StorageClass;
    }

    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    public function getReplicationStatus(): ?string
    {
        $this->initialize();

        return $this->ReplicationStatus;
    }

    public function getPartsCount(): ?int
    {
        $this->initialize();

        return $this->PartsCount;
    }

    public function getTagCount(): ?int
    {
        $this->initialize();

        return $this->TagCount;
    }

    public function getObjectLockMode(): ?string
    {
        $this->initialize();

        return $this->ObjectLockMode;
    }

    public function getObjectLockRetainUntilDate(): ?\DateTimeImmutable
    {
        $this->initialize();

        return $this->ObjectLockRetainUntilDate;
    }

    public function getObjectLockLegalHoldStatus(): ?string
    {
        $this->initialize();

        return $this->ObjectLockLegalHoldStatus;
    }
}
