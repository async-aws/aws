<?php

namespace AsyncAws\S3\Result;

use Symfony\Contracts\HttpClient\ResponseInterface;

trait DeleteObjectOutputTrait
{
    protected function populateFromResponse(ResponseInterface $response): void
    {
        $headers = $response->getHeaders(false);

        $this->DeleteMarker = $headers['x-amz-delete-marker'];
        $this->VersionId = $headers['x-amz-version-id'];
        $this->RequestCharged = $headers['x-amz-request-charged'];
    }
}
