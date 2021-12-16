<?php

namespace AsyncAws\Sns\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the details of a single Amazon SNS message along with an `Id` that identifies a message within the batch.
 */
final class PublishBatchRequestEntry
{
    /**
     * An identifier for the message in this batch.
     */
    private $id;

    /**
     * The body of the message.
     */
    private $message;

    /**
     * The subject of the batch message.
     */
    private $subject;

    /**
     * Set `MessageStructure` to `json` if you want to send a different message for each protocol. For example, using one
     * publish action, you can send a short message to your SMS subscribers and a longer message to your email subscribers.
     * If you set `MessageStructure` to `json`, the value of the `Message` parameter must:.
     */
    private $messageStructure;

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SNS message
     * attributes in the Amazon SNS Developer Guide.
     *
     * @see https://docs.aws.amazon.com/sns/latest/dg/sns-message-attributes.html
     */
    private $messageAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics.
     */
    private $messageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics.
     */
    private $messageGroupId;

    /**
     * @param array{
     *   Id: string,
     *   Message: string,
     *   Subject?: null|string,
     *   MessageStructure?: null|string,
     *   MessageAttributes?: null|array<string, MessageAttributeValue>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->message = $input['Message'] ?? null;
        $this->subject = $input['Subject'] ?? null;
        $this->messageStructure = $input['MessageStructure'] ?? null;
        $this->messageAttributes = isset($input['MessageAttributes']) ? array_map([MessageAttributeValue::class, 'create'], $input['MessageAttributes']) : null;
        $this->messageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->messageGroupId = $input['MessageGroupId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    public function getMessageAttributes(): array
    {
        return $this->messageAttributes ?? [];
    }

    public function getMessageDeduplicationId(): ?string
    {
        return $this->messageDeduplicationId;
    }

    public function getMessageGroupId(): ?string
    {
        return $this->messageGroupId;
    }

    public function getMessageStructure(): ?string
    {
        return $this->messageStructure;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->id) {
            throw new InvalidArgument(sprintf('Missing parameter "Id" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Id'] = $v;
        if (null === $v = $this->message) {
            throw new InvalidArgument(sprintf('Missing parameter "Message" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Message'] = $v;
        if (null !== $v = $this->subject) {
            $payload['Subject'] = $v;
        }
        if (null !== $v = $this->messageStructure) {
            $payload['MessageStructure'] = $v;
        }
        if (null !== $v = $this->messageAttributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["MessageAttributes.entry.$index.Name"] = $mapKey;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["MessageAttributes.entry.$index.Value.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->messageDeduplicationId) {
            $payload['MessageDeduplicationId'] = $v;
        }
        if (null !== $v = $this->messageGroupId) {
            $payload['MessageGroupId'] = $v;
        }

        return $payload;
    }
}
