<?php

namespace AsyncAws\Sqs\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

/**
 * The `MD5OfMessageBody` and `MessageId` elements.
 */
class SendMessageResult extends Result
{
    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $mD5OfMessageBody;

    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $mD5OfMessageAttributes;

    /**
     * An MD5 digest of the non-URL-encoded message system attribute string. You can use this attribute to verify that
     * Amazon SQS received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest.
     */
    private $mD5OfMessageSystemAttributes;

    /**
     * An attribute containing the `MessageId` of the message sent to the queue. For more information, see Queue and Message
     * Identifiers in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-message-identifiers.html
     */
    private $messageId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     */
    private $sequenceNumber;

    public function getMD5OfMessageAttributes(): ?string
    {
        $this->initialize();

        return $this->mD5OfMessageAttributes;
    }

    public function getMD5OfMessageBody(): ?string
    {
        $this->initialize();

        return $this->mD5OfMessageBody;
    }

    public function getMD5OfMessageSystemAttributes(): ?string
    {
        $this->initialize();

        return $this->mD5OfMessageSystemAttributes;
    }

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
        $data = $data->SendMessageResult;

        $this->mD5OfMessageBody = ($v = $data->MD5OfMessageBody) ? (string) $v : null;
        $this->mD5OfMessageAttributes = ($v = $data->MD5OfMessageAttributes) ? (string) $v : null;
        $this->mD5OfMessageSystemAttributes = ($v = $data->MD5OfMessageSystemAttributes) ? (string) $v : null;
        $this->messageId = ($v = $data->MessageId) ? (string) $v : null;
        $this->sequenceNumber = ($v = $data->SequenceNumber) ? (string) $v : null;
    }
}
