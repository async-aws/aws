<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Sqs\Enum\MessageSystemAttributeName;

/**
 * An Amazon SQS message.
 */
final class Message
{
    /**
     * A unique identifier for the message. A `MessageId`is considered unique across all Amazon Web Services accounts for an
     * extended period of time.
     *
     * @var string|null
     */
    private $messageId;

    /**
     * An identifier associated with the act of receiving the message. A new receipt handle is returned every time you
     * receive a message. When deleting a message, you provide the last received receipt handle to delete the message.
     *
     * @var string|null
     */
    private $receiptHandle;

    /**
     * An MD5 digest of the non-URL-encoded message body string.
     *
     * @var string|null
     */
    private $md5OfBody;

    /**
     * The message's contents (not URL-encoded).
     *
     * @var string|null
     */
    private $body;

    /**
     * A map of the attributes requested in `ReceiveMessage` to their respective values. Supported attributes:
     *
     * - `ApproximateReceiveCount`
     * - `ApproximateFirstReceiveTimestamp`
     * - `MessageDeduplicationId`
     * - `MessageGroupId`
     * - `SenderId`
     * - `SentTimestamp`
     * - `SequenceNumber`
     *
     * `ApproximateFirstReceiveTimestamp` and `SentTimestamp` are each returned as an integer representing the epoch time
     * [^1] in milliseconds.
     *
     * [^1]: http://en.wikipedia.org/wiki/Unix_time
     *
     * @var array<MessageSystemAttributeName::*, string>|null
     */
    private $attributes;

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
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SQS message
     * attributes [^1] in the *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-metadata.html#sqs-message-attributes
     *
     * @var array<string, MessageAttributeValue>|null
     */
    private $messageAttributes;

    /**
     * @param array{
     *   MessageId?: null|string,
     *   ReceiptHandle?: null|string,
     *   MD5OfBody?: null|string,
     *   Body?: null|string,
     *   Attributes?: null|array<MessageSystemAttributeName::*, string>,
     *   MD5OfMessageAttributes?: null|string,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->messageId = $input['MessageId'] ?? null;
        $this->receiptHandle = $input['ReceiptHandle'] ?? null;
        $this->md5OfBody = $input['MD5OfBody'] ?? null;
        $this->body = $input['Body'] ?? null;
        $this->attributes = $input['Attributes'] ?? null;
        $this->md5OfMessageAttributes = $input['MD5OfMessageAttributes'] ?? null;
        $this->messageAttributes = isset($input['MessageAttributes']) ? array_map([MessageAttributeValue::class, 'create'], $input['MessageAttributes']) : null;
    }

    /**
     * @param array{
     *   MessageId?: null|string,
     *   ReceiptHandle?: null|string,
     *   MD5OfBody?: null|string,
     *   Body?: null|string,
     *   Attributes?: null|array<MessageSystemAttributeName::*, string>,
     *   MD5OfMessageAttributes?: null|string,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     * }|Message $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<MessageSystemAttributeName::*, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getMd5OfBody(): ?string
    {
        return $this->md5OfBody;
    }

    public function getMd5OfMessageAttributes(): ?string
    {
        return $this->md5OfMessageAttributes;
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    public function getMessageAttributes(): array
    {
        return $this->messageAttributes ?? [];
    }

    public function getMessageId(): ?string
    {
        return $this->messageId;
    }

    public function getReceiptHandle(): ?string
    {
        return $this->receiptHandle;
    }
}
