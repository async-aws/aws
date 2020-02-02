<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait CreateBucketOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $headers = $response->getHeaders(false);

        $this->Location = $headers['Location'];
    }
}
