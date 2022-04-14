<?php

namespace AsyncAws\TimestreamQuery\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CancelQueryResponse extends Result
{
    /**
     * A `CancellationMessage` is returned when a `CancelQuery` request for the query specified by `QueryId` has already
     * been issued.
     */
    private $cancellationMessage;

    public function getCancellationMessage(): ?string
    {
        $this->initialize();

        return $this->cancellationMessage;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->cancellationMessage = isset($data['CancellationMessage']) ? (string) $data['CancellationMessage'] : null;
    }
}
