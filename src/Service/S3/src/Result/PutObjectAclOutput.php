<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class PutObjectAclOutput extends Result
{
    private $RequestCharged;

    /**
     * @return RequestCharged::*|null
     */
    public function getRequestCharged(): ?string
    {
        $this->initialize();

        return $this->RequestCharged;
    }

    protected function populateResult(ResponseInterface $response, HttpClientInterface $httpClient): void
    {
        $headers = $response->getHeaders(false);

        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;
    }
}
