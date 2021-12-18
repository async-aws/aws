<?php

namespace AsyncAws\Sqs\ValueObject;

/**
 * Encloses a `MessageId` for a successfully-enqueued message in a `SendMessageBatch.`.
 */
final class SendMessageBatchResultEntry
{
    /**
     * An identifier for the message in this batch.
     */
    private $id;

    /**
     * An identifier for the message.
     */
    private $messageId;

    /**
     * An MD5 digest of the non-URL-encoded message body string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $md5OfMessageBody;

    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $md5OfMessageAttributes;

    /**
     * An MD5 digest of the non-URL-encoded message system attribute string. You can use this attribute to verify that
     * Amazon SQS received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For
     * information about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $md5OfMessageSystemAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     */
    private $sequenceNumber;

    /**
     * @param array{
     *   Id: string,
     *   MessageId: string,
     *   MD5OfMessageBody: string,
     *   MD5OfMessageAttributes?: null|string,
     *   MD5OfMessageSystemAttributes?: null|string,
     *   SequenceNumber?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->messageId = $input['MessageId'] ?? null;
        $this->md5OfMessageBody = $input['MD5OfMessageBody'] ?? null;
        $this->md5OfMessageAttributes = $input['MD5OfMessageAttributes'] ?? null;
        $this->md5OfMessageSystemAttributes = $input['MD5OfMessageSystemAttributes'] ?? null;
        $this->sequenceNumber = $input['SequenceNumber'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMd5OfMessageAttributes(): ?string
    {
        return $this->md5OfMessageAttributes;
    }

    public function getMd5OfMessageBody(): string
    {
        return $this->md5OfMessageBody;
    }

    public function getMd5OfMessageSystemAttributes(): ?string
    {
        return $this->md5OfMessageSystemAttributes;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getSequenceNumber(): ?string
    {
        return $this->sequenceNumber;
    }
}
