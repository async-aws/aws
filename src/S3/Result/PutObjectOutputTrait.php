<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait PutObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->Expiration = $data->Expiration;
        $this->ETag = $data->ETag;
        $this->ServerSideEncryption = $data->ServerSideEncryption;
        $this->VersionId = $data->VersionId;
        $this->SSECustomerAlgorithm = $data->SSECustomerAlgorithm;
        $this->SSECustomerKeyMD5 = $data->SSECustomerKeyMD5;
        $this->SSEKMSKeyId = $data->SSEKMSKeyId;
        $this->SSEKMSEncryptionContext = $data->SSEKMSEncryptionContext;
        $this->RequestCharged = $data->RequestCharged;
    }
}
