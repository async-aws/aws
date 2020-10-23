<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class PublishResponse extends Result
{
    /**
     * Unique identifier assigned to the published message.
     */
    private $MessageId;

    /**
     * This response element applies only to FIFO (first-in-first-out) topics.
     */
    private $SequenceNumber;

    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->MessageId;
    }

    public function getSequenceNumber(): ?string
    {
        $this->initialize();

        return $this->SequenceNumber;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->PublishResult;

        $this->MessageId = ($v = $data->MessageId) ? (string) $v : null;
        $this->SequenceNumber = ($v = $data->SequenceNumber) ? (string) $v : null;
    }
}
