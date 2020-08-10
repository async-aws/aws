<?php

namespace AsyncAws\Rekognition\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteCollectionResponse extends Result
{
    /**
     * HTTP status code that indicates the result of the operation.
     */
    private $StatusCode;

    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->StatusCode;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->StatusCode = isset($data['StatusCode']) ? (int) $data['StatusCode'] : null;
    }
}
