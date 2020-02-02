<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait GetObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $headers = $response->getHeaders(false);

        $this->DeleteMarker = $headers['x-amz-delete-marker'];
        $this->AcceptRanges = $headers['accept-ranges'];
        $this->Expiration = $headers['x-amz-expiration'];
        $this->Restore = $headers['x-amz-restore'];
        $this->LastModified = $headers['Last-Modified'];
        $this->ContentLength = $headers['Content-Length'];
        $this->ETag = $headers['ETag'];
        $this->MissingMeta = $headers['x-amz-missing-meta'];
        $this->VersionId = $headers['x-amz-version-id'];
        $this->CacheControl = $headers['Cache-Control'];
        $this->ContentDisposition = $headers['Content-Disposition'];
        $this->ContentEncoding = $headers['Content-Encoding'];
        $this->ContentLanguage = $headers['Content-Language'];
        $this->ContentRange = $headers['Content-Range'];
        $this->ContentType = $headers['Content-Type'];
        $this->Expires = $headers['Expires'];
        $this->WebsiteRedirectLocation = $headers['x-amz-website-redirect-location'];
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'];
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'];
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-MD5'];
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'];
        $this->StorageClass = $headers['x-amz-storage-class'];
        $this->RequestCharged = $headers['x-amz-request-charged'];
        $this->ReplicationStatus = $headers['x-amz-replication-status'];
        $this->PartsCount = $headers['x-amz-mp-parts-count'];
        $this->TagCount = $headers['x-amz-tagging-count'];
        $this->ObjectLockMode = $headers['x-amz-object-lock-mode'];
        $this->ObjectLockRetainUntilDate = $headers['x-amz-object-lock-retain-until-date'];
        $this->ObjectLockLegalHoldStatus = $headers['x-amz-object-lock-legal-hold'];

        $this->Body = $response->getContent(false);
    }
}
