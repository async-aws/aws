<?php

namespace AsyncAws\RdsDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The response elements represent the output of a request to perform a rollback of a transaction.
 */
class RollbackTransactionResponse extends Result
{
    /**
     * The status of the rollback operation.
     */
    private $transactionStatus;

    public function getTransactionStatus(): ?string
    {
        $this->initialize();

        return $this->transactionStatus;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->transactionStatus = isset($data['transactionStatus']) ? (string) $data['transactionStatus'] : null;
    }
}
