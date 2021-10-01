<?php

namespace AsyncAws\AppSync\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class DeleteResolverResponse extends Result
{
    private $statusCode;

    public function getStatusCode(): ?int
    {
        $this->initialize();

        return $this->statusCode;
    }

    protected function populateResult(Response $response): void
    {
        $this->statusCode = $response->getStatusCode();
    }
}
