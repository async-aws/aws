<?php

namespace AsyncAws\Athena\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class StartQueryExecutionOutput extends Result
{
    /**
     * The unique ID of the query that ran as a result of this request.
     *
     * @var string|null
     */
    private $queryExecutionId;

    public function getQueryExecutionId(): ?string
    {
        $this->initialize();

        return $this->queryExecutionId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->queryExecutionId = isset($data['QueryExecutionId']) ? (string) $data['QueryExecutionId'] : null;
    }
}
