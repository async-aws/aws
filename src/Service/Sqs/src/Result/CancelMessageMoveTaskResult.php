<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CancelMessageMoveTaskResult extends Result
{
    /**
     * The approximate number of messages already moved to the destination queue.
     *
     * @var int|null
     */
    private $approximateNumberOfMessagesMoved;

    public function getApproximateNumberOfMessagesMoved(): ?int
    {
        $this->initialize();

        return $this->approximateNumberOfMessagesMoved;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->approximateNumberOfMessagesMoved = isset($data['ApproximateNumberOfMessagesMoved']) ? (int) $data['ApproximateNumberOfMessagesMoved'] : null;
    }
}
