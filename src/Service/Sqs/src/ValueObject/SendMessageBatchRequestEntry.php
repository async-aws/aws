<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Sqs\Enum\MessageSystemAttributeNameForSends;

/**
 * Contains the details of a single Amazon SQS message along with an `Id`.
 */
final class SendMessageBatchRequestEntry
{
    /**
     * An identifier for a message in this batch used to communicate the result.
     */
    private $id;

    /**
     * The body of the message.
     */
    private $messageBody;

    /**
     * The length of time, in seconds, for which a specific message is delayed. Valid values: 0 to 900. Maximum: 15 minutes.
     * Messages with a positive `DelaySeconds` value become available for processing after the delay period is finished. If
     * you don't specify a value, the default value for the queue is applied.
     */
    private $delaySeconds;

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SQS message
     * attributes in the *Amazon SQS Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-metadata.html#sqs-message-attributes
     */
    private $messageAttributes;

    /**
     * The message system attribute to send Each message system attribute consists of a `Name`, `Type`, and `Value`.
     */
    private $messageSystemAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     */
    private $messageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     */
    private $messageGroupId;

    /**
     * @param array{
     *   Id: string,
     *   MessageBody: string,
     *   DelaySeconds?: null|int,
     *   MessageAttributes?: null|array<string, MessageAttributeValue>,
     *   MessageSystemAttributes?: null|array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? null;
        $this->messageBody = $input['MessageBody'] ?? null;
        $this->delaySeconds = $input['DelaySeconds'] ?? null;
        $this->messageAttributes = isset($input['MessageAttributes']) ? array_map([MessageAttributeValue::class, 'create'], $input['MessageAttributes']) : null;
        $this->messageSystemAttributes = isset($input['MessageSystemAttributes']) ? array_map([MessageSystemAttributeValue::class, 'create'], $input['MessageSystemAttributes']) : null;
        $this->messageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->messageGroupId = $input['MessageGroupId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDelaySeconds(): ?int
    {
        return $this->delaySeconds;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    public function getMessageAttributes(): array
    {
        return $this->messageAttributes ?? [];
    }

    public function getMessageBody(): string
    {
        return $this->messageBody;
    }

    public function getMessageDeduplicationId(): ?string
    {
        return $this->messageDeduplicationId;
    }

    public function getMessageGroupId(): ?string
    {
        return $this->messageGroupId;
    }

    /**
     * @return array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue>
     */
    public function getMessageSystemAttributes(): array
    {
        return $this->messageSystemAttributes ?? [];
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
        if (null === $v = $this->messageBody) {
            throw new InvalidArgument(sprintf('Missing parameter "MessageBody" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['MessageBody'] = $v;
        if (null !== $v = $this->delaySeconds) {
            $payload['DelaySeconds'] = $v;
        }
        if (null !== $v = $this->messageAttributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["MessageAttribute.$index.Name"] = $mapKey;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["MessageAttribute.$index.Value.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->messageSystemAttributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                if (!MessageSystemAttributeNameForSends::exists($mapKey)) {
                    throw new InvalidArgument(sprintf('Invalid key for "%s". The value "%s" is not a valid "MessageSystemAttributeNameForSends".', __CLASS__, $mapKey));
                }
                ++$index;
                $payload["MessageSystemAttribute.$index.Name"] = $mapKey;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["MessageSystemAttribute.$index.Value.$bodyKey"] = $bodyValue;
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
