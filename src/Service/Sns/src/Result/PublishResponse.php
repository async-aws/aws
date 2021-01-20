<?php

namespace AsyncAws\Sns\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * Response for Publish action.
 */
class PublishResponse extends Result
{
    /**
     * Unique identifier assigned to the published message.
     */
    private $messageId;

    /**
     * This response element applies only to FIFO (first-in-first-out) topics.
     */
    private $sequenceNumber;

    public function getMessageId(): ?string
    {
        $this->initialize();

        return $this->messageId;
    }

    public function getSequenceNumber(): ?string
    {
        $this->initialize();

        return $this->sequenceNumber;
    }

    protected function populateResult(Response $response): void
    {
        $data = new \SimpleXMLElement($response->getContent());
        $data = $data->PublishResult;

        $this->messageId = ($v = $data->MessageId) ? (string) $v : null;
        $this->sequenceNumber = ($v = $data->SequenceNumber) ? (string) $v : null;
    }
}
