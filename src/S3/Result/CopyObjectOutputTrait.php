<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait CopyObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->CopyObjectResult = $data->CopyObjectResult;
        $this->Expiration = $data->Expiration;
        $this->CopySourceVersionId = $data->CopySourceVersionId;
        $this->VersionId = $data->VersionId;
        $this->ServerSideEncryption = $data->ServerSideEncryption;
        $this->SSECustomerAlgorithm = $data->SSECustomerAlgorithm;
        $this->SSECustomerKeyMD5 = $data->SSECustomerKeyMD5;
        $this->SSEKMSKeyId = $data->SSEKMSKeyId;
        $this->SSEKMSEncryptionContext = $data->SSEKMSEncryptionContext;
        $this->RequestCharged = $data->RequestCharged;
    }
}
