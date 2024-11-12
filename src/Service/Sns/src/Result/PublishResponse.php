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
     *
     * Length Constraint: Maximum 100 characters
     *
     * @var string|null
     */
    private $messageId;

    /**
     * This response element applies only to FIFO (first-in-first-out) topics.
     *
     * The sequence number is a large, non-consecutive number that Amazon SNS assigns to each message. The length of
     * `SequenceNumber` is 128 bits. `SequenceNumber` continues to increase for each `MessageGroupId`.
     *
     * @var string|null
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

        $this->messageId = (null !== $v = $data->MessageId[0]) ? (string) $v : null;
        $this->sequenceNumber = (null !== $v = $data->SequenceNumber[0]) ? (string) $v : null;
    }
}
