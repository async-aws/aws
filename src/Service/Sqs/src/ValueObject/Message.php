<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Sqs\Enum\MessageSystemAttributeName;

/**
 * An Amazon SQS message.
 */
final class Message
{
    /**
     * A unique identifier for the message. A `MessageId`is considered unique across all AWS accounts for an extended period
     * of time.
     */
    private $messageId;

    /**
     * An identifier associated with the act of receiving the message. A new receipt handle is returned every time you
     * receive a message. When deleting a message, you provide the last received receipt handle to delete the message.
     */
    private $receiptHandle;

    /**
     * An MD5 digest of the non-URL-encoded message body string.
     */
    private $mD5OfBody;

    /**
     * The message's contents (not URL-encoded).
     */
    private $body;

    /**
     * A map of the attributes requested in `ReceiveMessage` to their respective values. Supported attributes:.
     */
    private $attributes;

    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    private $mD5OfMessageAttributes;

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SQS Message
     * Attributes in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-metadata.html#sqs-message-attributes
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
     *   MessageAttributes?: null|array<string, MessageAttributeValue>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->messageId = $input['MessageId'] ?? null;
        $this->receiptHandle = $input['ReceiptHandle'] ?? null;
        $this->mD5OfBody = $input['MD5OfBody'] ?? null;
        $this->body = $input['Body'] ?? null;
        $this->attributes = $input['Attributes'] ?? null;
        $this->mD5OfMessageAttributes = $input['MD5OfMessageAttributes'] ?? null;
        $this->messageAttributes = isset($input['MessageAttributes']) ? array_map([MessageAttributeValue::class, 'create'], $input['MessageAttributes']) : null;
    }

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

    public function getMD5OfBody(): ?string
    {
        return $this->mD5OfBody;
    }

    public function getMD5OfMessageAttributes(): ?string
    {
        return $this->mD5OfMessageAttributes;
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
