<?php

namespace AsyncAws\S3\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;
use AsyncAws\S3\Enum\RequestCharged;

class AbortMultipartUploadOutput extends Result
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

    protected function populateResult(Response $response): void
    {
        $headers = $response->getHeaders();

        $this->RequestCharged = $headers['x-amz-request-charged'][0] ?? null;
    }
}
