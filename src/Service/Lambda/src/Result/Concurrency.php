<?php

namespace AsyncAws\Lambda\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class Concurrency extends Result
{
    /**
     * The number of concurrent executions that are reserved for this function. For more information, see Managing Lambda
     * reserved concurrency [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-concurrency.html
     *
     * @var int|null
     */
    private $reservedConcurrentExecutions;

    public function getReservedConcurrentExecutions(): ?int
    {
        $this->initialize();

        return $this->reservedConcurrentExecutions;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->reservedConcurrentExecutions = isset($data['ReservedConcurrentExecutions']) ? (int) $data['ReservedConcurrentExecutions'] : null;
    }
}
