<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CreateBucketOutput extends Result
{
    private $Location;

    public function getLocation(): ?string
    {
        $this->initialize();

        return $this->Location;
    }

    protected function populateResult(ResponseInterface $response, ?HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->Location = $headers['Location'];
    }
}
