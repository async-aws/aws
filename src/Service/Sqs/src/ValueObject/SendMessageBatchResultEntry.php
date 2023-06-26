<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Encloses a `MessageId` for a successfully-enqueued message in a `SendMessageBatch.`.
 */
final class SendMessageBatchResultEntry
{
    /**
     * An identifier for the message in this batch.
     *
     * @var string
     */
    private $id;

    /**
     * An identifier for the message.
     *
     * @var string
     */
    private $messageId;

    /**
     * An MD5 digest of the non-URL-encoded message body string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321 [^1].
     *
     * [^1]: https://www.ietf.org/rfc/rfc1321.txt
     *
     * @var string
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
     * Amazon SQS received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For
     * information about MD5, see RFC1321 [^1].
     *
     * [^1]: https://www.ietf.org/rfc/rfc1321.txt
     *
     * @var string|null
     */
    private $md5OfMessageSystemAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * The large, non-consecutive number that Amazon SQS assigns to each message.
     *
     * The length of `SequenceNumber` is 128 bits. As `SequenceNumber` continues to increase for a particular
     * `MessageGroupId`.
     *
     * @var string|null
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
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->messageId = $input['MessageId'] ?? $this->throwException(new InvalidArgument('Missing required field "MessageId".'));
        $this->md5OfMessageBody = $input['MD5OfMessageBody'] ?? $this->throwException(new InvalidArgument('Missing required field "MD5OfMessageBody".'));
        $this->md5OfMessageAttributes = $input['MD5OfMessageAttributes'] ?? null;
        $this->md5OfMessageSystemAttributes = $input['MD5OfMessageSystemAttributes'] ?? null;
        $this->sequenceNumber = $input['SequenceNumber'] ?? null;
    }

    /**
     * @param array{
     *   Id: string,
     *   MessageId: string,
     *   MD5OfMessageBody: string,
     *   MD5OfMessageAttributes?: null|string,
     *   MD5OfMessageSystemAttributes?: null|string,
     *   SequenceNumber?: null|string,
     * }|SendMessageBatchResultEntry $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
