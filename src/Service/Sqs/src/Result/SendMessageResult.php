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
     * An MD5 digest of the non-URL-encoded message body string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321 [^1].
     *
     * [^1]: https://www.ietf.org/rfc/rfc1321.txt
     *
     * @var string|null
     */
    private $md5OfMessageBody;

    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321 [^1].
     *
     * [^1]: https://www.ietf.org/rfc/rfc1321.txt
     *
     * @var string|null
     */
    private $md5OfMessageAttributes;

    /**
     * An MD5 digest of the non-URL-encoded message system attribute string. You can use this attribute to verify that
     * Amazon SQS received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest.
     *
     * @var string|null
     */
    private $md5OfMessageSystemAttributes;

    /**
     * An attribute containing the `MessageId` of the message sent to the queue. For more information, see Queue and Message
     * Identifiers [^1] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-message-identifiers.html
     *
     * @var string|null
     */
    private $messageId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * The large, non-consecutive number that Amazon SQS assigns to each message.
     *
     * The length of `SequenceNumber` is 128 bits. `SequenceNumber` continues to increase for a particular `MessageGroupId`.
     *
     * @var string|null
     */
    private $sequenceNumber;

    public function getMd5OfMessageAttributes(): ?string
    {
        $this->initialize();

        return $this->md5OfMessageAttributes;
    }

    public function getMd5OfMessageBody(): ?string
    {
        $this->initialize();

        return $this->md5OfMessageBody;
    }

    public function getMd5OfMessageSystemAttributes(): ?string
    {
        $this->initialize();

        return $this->md5OfMessageSystemAttributes;
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
        $data = $response->toArray();

        $this->md5OfMessageBody = isset($data['MD5OfMessageBody']) ? (string) $data['MD5OfMessageBody'] : null;
        $this->md5OfMessageAttributes = isset($data['MD5OfMessageAttributes']) ? (string) $data['MD5OfMessageAttributes'] : null;
        $this->md5OfMessageSystemAttributes = isset($data['MD5OfMessageSystemAttributes']) ? (string) $data['MD5OfMessageSystemAttributes'] : null;
        $this->messageId = isset($data['MessageId']) ? (string) $data['MessageId'] : null;
        $this->sequenceNumber = isset($data['SequenceNumber']) ? (string) $data['SequenceNumber'] : null;
    }
}
