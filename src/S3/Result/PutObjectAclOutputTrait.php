<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait PutObjectAclOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $headers = $response->getHeaders(false);

        $this->RequestCharged = $headers['x-amz-request-charged'];
    }
}
