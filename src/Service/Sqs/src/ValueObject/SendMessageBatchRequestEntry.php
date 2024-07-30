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
     *
     * > The `Id`s of a batch request need to be unique within a request.
     * >
     * > This identifier can have up to 80 characters. The following characters are accepted: alphanumeric characters,
     * > hyphens(-), and underscores (_).
     *
     * @var string
     */
    private $id;

    /**
     * The body of the message.
     *
     * @var string
     */
    private $messageBody;

    /**
     * The length of time, in seconds, for which a specific message is delayed. Valid values: 0 to 900. Maximum: 15 minutes.
     * Messages with a positive `DelaySeconds` value become available for processing after the delay period is finished. If
     * you don't specify a value, the default value for the queue is applied.
     *
     * > When you set `FifoQueue`, you can't set `DelaySeconds` per message. You can set this parameter only on a queue
     * > level.
     *
     * @var int|null
     */
    private $delaySeconds;

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
     * The message system attribute to send Each message system attribute consists of a `Name`, `Type`, and `Value`.
     *
     * ! - Currently, the only supported message system attribute is `AWSTraceHeader`. Its type must be `String` and its
     * !   value must be a correctly formatted X-Ray trace header string.
     * ! - The size of a message system attribute doesn't count towards the total size of a message.
     * !
     *
     * @var array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue>|null
     */
    private $messageSystemAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * The token used for deduplication of messages within a 5-minute minimum deduplication interval. If a message with a
     * particular `MessageDeduplicationId` is sent successfully, subsequent messages with the same `MessageDeduplicationId`
     * are accepted successfully but aren't delivered. For more information, see Exactly-once processing [^1] in the *Amazon
     * SQS Developer Guide*.
     *
     * - Every message must have a unique `MessageDeduplicationId`,
     *
     *   - You may provide a `MessageDeduplicationId` explicitly.
     *   - If you aren't able to provide a `MessageDeduplicationId` and you enable `ContentBasedDeduplication` for your
     *     queue, Amazon SQS uses a SHA-256 hash to generate the `MessageDeduplicationId` using the body of the message (but
     *     not the attributes of the message).
     *   - If you don't provide a `MessageDeduplicationId` and the queue doesn't have `ContentBasedDeduplication` set, the
     *     action fails with an error.
     *   - If the queue has `ContentBasedDeduplication` set, your `MessageDeduplicationId` overrides the generated one.
     *
     * - When `ContentBasedDeduplication` is in effect, messages with identical content sent within the deduplication
     *   interval are treated as duplicates and only one copy of the message is delivered.
     * - If you send one message with `ContentBasedDeduplication` enabled and then another message with a
     *   `MessageDeduplicationId` that is the same as the one generated for the first `MessageDeduplicationId`, the two
     *   messages are treated as duplicates and only one copy of the message is delivered.
     *
     * > The `MessageDeduplicationId` is available to the consumer of the message (this can be useful for troubleshooting
     * > delivery issues).
     * >
     * > If a message is sent successfully but the acknowledgement is lost and the message is resent with the same
     * > `MessageDeduplicationId` after the deduplication interval, Amazon SQS can't detect duplicate messages.
     * >
     * > Amazon SQS continues to keep track of the message deduplication ID even after the message is received and deleted.
     *
     * The length of `MessageDeduplicationId` is 128 characters. `MessageDeduplicationId` can contain alphanumeric
     * characters (`a-z`, `A-Z`, `0-9`) and punctuation (`!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~`).
     *
     * For best practices of using `MessageDeduplicationId`, see Using the MessageDeduplicationId Property [^2] in the
     * *Amazon SQS Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues-exactly-once-processing.html
     * [^2]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-messagededuplicationid-property.html
     *
     * @var string|null
     */
    private $messageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * The tag that specifies that a message belongs to a specific message group. Messages that belong to the same message
     * group are processed in a FIFO manner (however, messages in different message groups might be processed out of order).
     * To interleave multiple ordered streams within a single queue, use `MessageGroupId` values (for example, session data
     * for multiple users). In this scenario, multiple consumers can process the queue, but the session data of each user is
     * processed in a FIFO fashion.
     *
     * - You must associate a non-empty `MessageGroupId` with a message. If you don't provide a `MessageGroupId`, the action
     *   fails.
     * - `ReceiveMessage` might return messages with multiple `MessageGroupId` values. For each `MessageGroupId`, the
     *   messages are sorted by time sent. The caller can't specify a `MessageGroupId`.
     *
     * The length of `MessageGroupId` is 128 characters. Valid values: alphanumeric characters and punctuation
     * `(!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~)`.
     *
     * For best practices of using `MessageGroupId`, see Using the MessageGroupId Property [^1] in the *Amazon SQS Developer
     * Guide*.
     *
     * ! `MessageGroupId` is required for FIFO queues. You can't use it for Standard queues.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-messagegroupid-property.html
     *
     * @var string|null
     */
    private $messageGroupId;

    /**
     * @param array{
     *   Id: string,
     *   MessageBody: string,
     *   DelaySeconds?: null|int,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageSystemAttributes?: null|array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->messageBody = $input['MessageBody'] ?? $this->throwException(new InvalidArgument('Missing required field "MessageBody".'));
        $this->delaySeconds = $input['DelaySeconds'] ?? null;
        $this->messageAttributes = isset($input['MessageAttributes']) ? array_map([MessageAttributeValue::class, 'create'], $input['MessageAttributes']) : null;
        $this->messageSystemAttributes = isset($input['MessageSystemAttributes']) ? array_map([MessageSystemAttributeValue::class, 'create'], $input['MessageSystemAttributes']) : null;
        $this->messageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->messageGroupId = $input['MessageGroupId'] ?? null;
    }

    /**
     * @param array{
     *   Id: string,
     *   MessageBody: string,
     *   DelaySeconds?: null|int,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageSystemAttributes?: null|array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     * }|SendMessageBatchRequestEntry $input
     */
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
        $v = $this->id;
        $payload['Id'] = $v;
        $v = $this->messageBody;
        $payload['MessageBody'] = $v;
        if (null !== $v = $this->delaySeconds) {
            $payload['DelaySeconds'] = $v;
        }
        if (null !== $v = $this->messageAttributes) {
            if (empty($v)) {
                $payload['MessageAttributes'] = new \stdClass();
            } else {
                $payload['MessageAttributes'] = [];
                foreach ($v as $name => $mv) {
                    $payload['MessageAttributes'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->messageSystemAttributes) {
            if (empty($v)) {
                $payload['MessageSystemAttributes'] = new \stdClass();
            } else {
                $payload['MessageSystemAttributes'] = [];
                foreach ($v as $name => $mv) {
                    if (!MessageSystemAttributeNameForSends::exists($name)) {
                        throw new InvalidArgument(\sprintf('Invalid key for "%s". The value "%s" is not a valid "MessageSystemAttributeNameForSends".', __CLASS__, $name));
                    }
                    $payload['MessageSystemAttributes'][$name] = $mv->requestBody();
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
