<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait PutObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $headers = $response->getHeaders(false);

        $this->Expiration = $headers['x-amz-expiration'];
        $this->ETag = $headers['ETag'];
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'];
        $this->VersionId = $headers['x-amz-version-id'];
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'];
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-MD5'];
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'];
        $this->SSEKMSEncryptionContext = $headers['x-amz-server-side-encryption-context'];
        $this->RequestCharged = $headers['x-amz-request-charged'];
    }
}
