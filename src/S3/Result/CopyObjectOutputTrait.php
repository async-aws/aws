<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait CopyObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $data = new \SimpleXMLElement($response->getContent(false));

        // TODO Verify correctness
        $this->CopyObjectResult = $this->xmlValueOrNull($data->CopyObjectResult);
        $this->Expiration = $this->xmlValueOrNull($data->Expiration);
        $this->CopySourceVersionId = $this->xmlValueOrNull($data->CopySourceVersionId);
        $this->VersionId = $this->xmlValueOrNull($data->VersionId);
        $this->ServerSideEncryption = $this->xmlValueOrNull($data->ServerSideEncryption);
        $this->SSECustomerAlgorithm = $this->xmlValueOrNull($data->SSECustomerAlgorithm);
        $this->SSECustomerKeyMD5 = $this->xmlValueOrNull($data->SSECustomerKeyMD5);
        $this->SSEKMSKeyId = $this->xmlValueOrNull($data->SSEKMSKeyId);
        $this->SSEKMSEncryptionContext = $this->xmlValueOrNull($data->SSEKMSEncryptionContext);
        $this->RequestCharged = $this->xmlValueOrNull($data->RequestCharged);
    }
}
