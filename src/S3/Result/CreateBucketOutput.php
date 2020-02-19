<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CreateBucketOutput extends Result
{
    /**
     * Specifies the Region where the bucket will be created. If you are creating a bucket on the US East (N. Virginia)
     * Region (us-east-1), you do not need to specify the location.
     */
    private $Location;

    /**
     * Ensure current request is resolved and right exception is thrown.
     */
    public function __destruct()
    {
        $this->resolve();
    }

    public function getLocation(): ?string
    {
        $this->initialize();

        return $this->Location;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->Location = $headers['location'][0] ?? null;
    }
}
