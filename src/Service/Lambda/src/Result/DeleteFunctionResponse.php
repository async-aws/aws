<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteFunctionResponse extends Result
{
    /**
     * The HTTP status code returned by the operation.
     *
     * @var int|null
     */
    private $statusCode;

    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->statusCode;
    }

    protected function populateResult(Response $response): void
    {
        $this->statusCode = $response->getStatusCode();

        $data = $response->toArray();

        $this->statusCode = isset($data['StatusCode']) ? (int) $data['StatusCode'] : null;
    }
}
