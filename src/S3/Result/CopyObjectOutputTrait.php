<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait CopyObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $headers = $response->getHeaders(false);

        $this->Expiration = $headers['x-amz-expiration'];
        $this->CopySourceVersionId = $headers['x-amz-copy-source-version-id'];
        $this->VersionId = $headers['x-amz-version-id'];
        $this->ServerSideEncryption = $headers['x-amz-server-side-encryption'];
        $this->SSECustomerAlgorithm = $headers['x-amz-server-side-encryption-customer-algorithm'];
        $this->SSECustomerKeyMD5 = $headers['x-amz-server-side-encryption-customer-key-MD5'];
        $this->SSEKMSKeyId = $headers['x-amz-server-side-encryption-aws-kms-key-id'];
        $this->SSEKMSEncryptionContext = $headers['x-amz-server-side-encryption-context'];
        $this->RequestCharged = $headers['x-amz-request-charged'];

        $data = new \SimpleXMLElement($response->getContent(false));

        $this->CopyObjectResult = new CopyObjectResult([
        'ETag' => $this->xmlValueOrNull($data->ETag, 'string'),
        'LastModified' => $this->xmlValueOrNull($data->LastModified, '\DateTimeImmutable'),
        ]);
    }
}
