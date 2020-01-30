<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Aws\Result;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CreateBucketOutput extends Result
{
    private $Location;

    protected function populateFromResponse(ResponseInterface $response): void
    {
        // TODO implement me
    }

    public function getLocation(): string
    {
        $this->initialize();
        return $this->Location;
    }
}
