<?php

namespace AsyncAws\RdsDataService\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class BeginTransactionResponse extends Result
{
    /**
     * The transaction ID of the transaction started by the call.
     */
    private $transactionId;

    public function getTransactionId(): ?string
    {
        $this->initialize();

        return $this->transactionId;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->transactionId = isset($data['transactionId']) ? (string) $data['transactionId'] : null;
    }
}
