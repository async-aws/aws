<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait GetObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->Body = $data->Body;
        $this->DeleteMarker = $data->DeleteMarker;
        $this->AcceptRanges = $data->AcceptRanges;
        $this->Expiration = $data->Expiration;
        $this->Restore = $data->Restore;
        $this->LastModified = $data->LastModified;
        $this->ContentLength = $data->ContentLength;
        $this->ETag = $data->ETag;
        $this->MissingMeta = $data->MissingMeta;
        $this->VersionId = $data->VersionId;
        $this->CacheControl = $data->CacheControl;
        $this->ContentDisposition = $data->ContentDisposition;
        $this->ContentEncoding = $data->ContentEncoding;
        $this->ContentLanguage = $data->ContentLanguage;
        $this->ContentRange = $data->ContentRange;
        $this->ContentType = $data->ContentType;
        $this->Expires = $data->Expires;
        $this->WebsiteRedirectLocation = $data->WebsiteRedirectLocation;
        $this->ServerSideEncryption = $data->ServerSideEncryption;
        $this->Metadata = $data->Metadata;
        $this->SSECustomerAlgorithm = $data->SSECustomerAlgorithm;
        $this->SSECustomerKeyMD5 = $data->SSECustomerKeyMD5;
        $this->SSEKMSKeyId = $data->SSEKMSKeyId;
        $this->StorageClass = $data->StorageClass;
        $this->RequestCharged = $data->RequestCharged;
        $this->ReplicationStatus = $data->ReplicationStatus;
        $this->PartsCount = $data->PartsCount;
        $this->TagCount = $data->TagCount;
        $this->ObjectLockMode = $data->ObjectLockMode;
        $this->ObjectLockRetainUntilDate = $data->ObjectLockRetainUntilDate;
        $this->ObjectLockLegalHoldStatus = $data->ObjectLockLegalHoldStatus;
    }
}
