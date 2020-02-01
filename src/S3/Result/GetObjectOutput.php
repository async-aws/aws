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
        $this->resolve();

        return $this->Body;
    }

    public function getDeleteMarker(): ?bool
    {
        $this->resolve();

        return $this->DeleteMarker;
    }

    public function getAcceptRanges(): ?string
    {
        $this->resolve();

        return $this->AcceptRanges;
    }

    public function getExpiration(): ?string
    {
        $this->resolve();

        return $this->Expiration;
    }

    public function getRestore(): ?string
    {
        $this->resolve();

        return $this->Restore;
    }

    public function getLastModified(): ?int
    {
        $this->resolve();

        return $this->LastModified;
    }

    public function getContentLength(): ?string
    {
        $this->resolve();

        return $this->ContentLength;
    }

    public function getETag(): ?string
    {
        $this->resolve();

        return $this->ETag;
    }

    public function getMissingMeta(): ?int
    {
        $this->resolve();

        return $this->MissingMeta;
    }

    public function getVersionId(): ?string
    {
        $this->resolve();

        return $this->VersionId;
    }

    public function getCacheControl(): ?string
    {
        $this->resolve();

        return $this->CacheControl;
    }

    public function getContentDisposition(): ?string
    {
        $this->resolve();

        return $this->ContentDisposition;
    }

    public function getContentEncoding(): ?string
    {
        $this->resolve();

        return $this->ContentEncoding;
    }

    public function getContentLanguage(): ?string
    {
        $this->resolve();

        return $this->ContentLanguage;
    }

    public function getContentRange(): ?string
    {
        $this->resolve();

        return $this->ContentRange;
    }

    public function getContentType(): ?string
    {
        $this->resolve();

        return $this->ContentType;
    }

    public function getExpires(): ?int
    {
        $this->resolve();

        return $this->Expires;
    }

    public function getWebsiteRedirectLocation(): ?string
    {
        $this->resolve();

        return $this->WebsiteRedirectLocation;
    }

    public function getServerSideEncryption(): ?string
    {
        $this->resolve();

        return $this->ServerSideEncryption;
    }

    public function getMetadata(): ?array
    {
        $this->resolve();

        return $this->Metadata;
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

    public function getStorageClass(): ?string
    {
        $this->resolve();

        return $this->StorageClass;
    }

    public function getRequestCharged(): ?string
    {
        $this->resolve();

        return $this->RequestCharged;
    }

    public function getReplicationStatus(): ?string
    {
        $this->resolve();

        return $this->ReplicationStatus;
    }

    public function getPartsCount(): ?int
    {
        $this->resolve();

        return $this->PartsCount;
    }

    public function getTagCount(): ?int
    {
        $this->resolve();

        return $this->TagCount;
    }

    public function getObjectLockMode(): ?string
    {
        $this->resolve();

        return $this->ObjectLockMode;
    }

    public function getObjectLockRetainUntilDate(): ?int
    {
        $this->resolve();

        return $this->ObjectLockRetainUntilDate;
    }

    public function getObjectLockLegalHoldStatus(): ?string
    {
        $this->resolve();

        return $this->ObjectLockLegalHoldStatus;
    }
}
