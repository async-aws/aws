<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait HeadObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->DeleteMarker = $this->xmlValueOrNull($data->DeleteMarker);
        $this->AcceptRanges = $this->xmlValueOrNull($data->AcceptRanges);
        $this->Expiration = $this->xmlValueOrNull($data->Expiration);
        $this->Restore = $this->xmlValueOrNull($data->Restore);
        $this->LastModified = $this->xmlValueOrNull($data->LastModified);
        $this->ContentLength = $this->xmlValueOrNull($data->ContentLength);
        $this->ETag = $this->xmlValueOrNull($data->ETag);
        $this->MissingMeta = $this->xmlValueOrNull($data->MissingMeta);
        $this->VersionId = $this->xmlValueOrNull($data->VersionId);
        $this->CacheControl = $this->xmlValueOrNull($data->CacheControl);
        $this->ContentDisposition = $this->xmlValueOrNull($data->ContentDisposition);
        $this->ContentEncoding = $this->xmlValueOrNull($data->ContentEncoding);
        $this->ContentLanguage = $this->xmlValueOrNull($data->ContentLanguage);
        $this->ContentType = $this->xmlValueOrNull($data->ContentType);
        $this->Expires = $this->xmlValueOrNull($data->Expires);
        $this->WebsiteRedirectLocation = $this->xmlValueOrNull($data->WebsiteRedirectLocation);
        $this->ServerSideEncryption = $this->xmlValueOrNull($data->ServerSideEncryption);
        $this->Metadata = $this->xmlValueOrNull($data->Metadata);
        $this->SSECustomerAlgorithm = $this->xmlValueOrNull($data->SSECustomerAlgorithm);
        $this->SSECustomerKeyMD5 = $this->xmlValueOrNull($data->SSECustomerKeyMD5);
        $this->SSEKMSKeyId = $this->xmlValueOrNull($data->SSEKMSKeyId);
        $this->StorageClass = $this->xmlValueOrNull($data->StorageClass);
        $this->RequestCharged = $this->xmlValueOrNull($data->RequestCharged);
        $this->ReplicationStatus = $this->xmlValueOrNull($data->ReplicationStatus);
        $this->PartsCount = $this->xmlValueOrNull($data->PartsCount);
        $this->ObjectLockMode = $this->xmlValueOrNull($data->ObjectLockMode);
        $this->ObjectLockRetainUntilDate = $this->xmlValueOrNull($data->ObjectLockRetainUntilDate);
        $this->ObjectLockLegalHoldStatus = $this->xmlValueOrNull($data->ObjectLockLegalHoldStatus);
    }
}
