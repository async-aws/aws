<?php

namespace AsyncAws\Sqs\Result;

class Message
{
    private $MessageId;

    private $ReceiptHandle;

    private $MD5OfBody;

    private $Body;

    private $Attributes = [];

    private $MD5OfMessageAttributes;

    private $MessageAttributes = [];

    /**
     * @param array{
     *   MessageId: null|string,
     *   ReceiptHandle: null|string,
     *   MD5OfBody: null|string,
     *   Body: null|string,
     *   Attributes: null|string[],
     *   MD5OfMessageAttributes: null|string,
     *   MessageAttributes: null|\AsyncAws\Sqs\Result\MessageAttributeValue[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->MessageId = $input['MessageId'];
        $this->ReceiptHandle = $input['ReceiptHandle'];
        $this->MD5OfBody = $input['MD5OfBody'];
        $this->Body = $input['Body'];
        $this->Attributes = $input['Attributes'] ?? [];
        $this->MD5OfMessageAttributes = $input['MD5OfMessageAttributes'];
        $this->MessageAttributes = array_map(function ($item) { return MessageAttributeValue::create($item); }, $input['MessageAttributes'] ?? []);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * A map of the attributes requested in `ReceiveMessage` to their respective values. Supported attributes:.
     *
     * @return string[]
     */
    public function getAttributes(): array
    {
        return $this->Attributes;
    }

    /**
     * The message's contents (not URL-encoded).
     */
    public function getBody(): ?string
    {
        return $this->Body;
    }

    /**
     * An MD5 digest of the non-URL-encoded message body string.
     */
    public function getMD5OfBody(): ?string
    {
        return $this->MD5OfBody;
    }

    /**
     * An MD5 digest of the non-URL-encoded message attribute string. You can use this attribute to verify that Amazon SQS
     * received the message correctly. Amazon SQS URL-decodes the message before creating the MD5 digest. For information
     * about MD5, see RFC1321.
     *
     * @see https://www.ietf.org/rfc/rfc1321.txt
     */
    public function getMD5OfMessageAttributes(): ?string
    {
        return $this->MD5OfMessageAttributes;
    }

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SQS Message
     * Attributes in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-attributes.html
     *
     * @return MessageAttributeValue[]
     */
    public function getMessageAttributes(): array
    {
        return $this->MessageAttributes;
    }

    /**
     * A unique identifier for the message. A `MessageId`is considered unique across all AWS accounts for an extended period
     * of time.
     */
    public function getMessageId(): ?string
    {
        return $this->MessageId;
    }

    /**
     * An identifier associated with the act of receiving the message. A new receipt handle is returned every time you
     * receive a message. When deleting a message, you provide the last received receipt handle to delete the message.
     */
    public function getReceiptHandle(): ?string
    {
        return $this->ReceiptHandle;
    }
}
