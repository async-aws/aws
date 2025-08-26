<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sqs\Enum\MessageSystemAttributeNameForSends;
use AsyncAws\Sqs\ValueObject\MessageAttributeValue;
use AsyncAws\Sqs\ValueObject\MessageSystemAttributeValue;

final class SendMessageRequest extends Input
{
    /**
     * The URL of the Amazon SQS queue to which a message is sent.
     *
     * Queue URLs and names are case-sensitive.
     *
     * @required
     *
     * @var string|null
     */
    private $queueUrl;

    /**
     * The message to send. The minimum size is one character. The maximum size is 1 MiB or 1,048,576 bytes.
     *
     * ! A message can include only XML, JSON, and unformatted text. The following Unicode characters are allowed. For more
     * ! information, see the W3C specification for characters [^1].
     * !
     * ! `#x9` | `#xA` | `#xD` | `#x20` to `#xD7FF` | `#xE000` to `#xFFFD` | `#x10000` to `#x10FFFF`
     * !
     * ! Amazon SQS does not throw an exception or completely reject the message if it contains invalid characters. Instead,
     * ! it replaces those invalid characters with U+FFFD before storing the message in the queue, as long as the message
     * ! body contains at least one valid character.
     *
     * [^1]: http://www.w3.org/TR/REC-xml/#charsets
     *
     * @required
     *
     * @var string|null
     */
    private $messageBody;

    /**
     * The length of time, in seconds, for which to delay a specific message. Valid values: 0 to 900. Maximum: 15 minutes.
     * Messages with a positive `DelaySeconds` value become available for processing after the delay period is finished. If
     * you don't specify a value, the default value for the queue applies.
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
     * The message system attribute to send. Each message system attribute consists of a `Name`, `Type`, and `Value`.
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
     * The token used for deduplication of sent messages. If a message with a particular `MessageDeduplicationId` is sent
     * successfully, any messages sent with the same `MessageDeduplicationId` are accepted successfully but aren't delivered
     * during the 5-minute deduplication interval. For more information, see Exactly-once processing [^1] in the *Amazon SQS
     * Developer Guide*.
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
     * The maximum length of `MessageDeduplicationId` is 128 characters. `MessageDeduplicationId` can contain alphanumeric
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
     * `MessageGroupId` is an attribute used in Amazon SQS FIFO (First-In-First-Out) and standard queues. In FIFO queues,
     * `MessageGroupId` organizes messages into distinct groups. Messages within the same message group are always processed
     * one at a time, in strict order, ensuring that no two messages from the same group are processed simultaneously. In
     * standard queues, using `MessageGroupId` enables fair queues. It is used to identify the tenant a message belongs to,
     * helping maintain consistent message dwell time across all tenants during noisy neighbor events. Unlike FIFO queues,
     * messages with the same `MessageGroupId` can be processed in parallel, maintaining the high throughput of standard
     * queues.
     *
     * - **FIFO queues:**`MessageGroupId` acts as the tag that specifies that a message belongs to a specific message group.
     *   Messages that belong to the same message group are processed in a FIFO manner (however, messages in different
     *   message groups might be processed out of order). To interleave multiple ordered streams within a single queue, use
     *   `MessageGroupId` values (for example, session data for multiple users). In this scenario, multiple consumers can
     *   process the queue, but the session data of each user is processed in a FIFO fashion.
     *
     *   If you do not provide a `MessageGroupId` when sending a message to a FIFO queue, the action fails.
     *
     *   `ReceiveMessage` might return messages with multiple `MessageGroupId` values. For each `MessageGroupId`, the
     *   messages are sorted by time sent.
     * - **Standard queues:**Use `MessageGroupId` in standard queues to enable fair queues. The `MessageGroupId` identifies
     *   the tenant a message belongs to. A tenant can be any entity that shares a queue with others, such as your customer,
     *   a client application, or a request type. When one tenant sends a disproportionately large volume of messages or has
     *   messages that require longer processing time, fair queues ensure other tenants' messages maintain low dwell time.
     *   This preserves quality of service for all tenants while maintaining the scalability and throughput of standard
     *   queues. We recommend that you include a `MessageGroupId` in all messages when using fair queues.
     *
     * The length of `MessageGroupId` is 128 characters. Valid values: alphanumeric characters and punctuation
     * `(!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~)`.
     *
     * For best practices of using `MessageGroupId`, see Using the MessageGroupId Property [^1] in the *Amazon SQS Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-messagegroupid-property.html
     *
     * @var string|null
     */
    private $messageGroupId;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   MessageBody?: string,
     *   DelaySeconds?: null|int,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageSystemAttributes?: null|array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueUrl = $input['QueueUrl'] ?? null;
        $this->messageBody = $input['MessageBody'] ?? null;
        $this->delaySeconds = $input['DelaySeconds'] ?? null;

        if (isset($input['MessageAttributes'])) {
            $this->messageAttributes = [];
            foreach ($input['MessageAttributes'] as $key => $item) {
                $this->messageAttributes[$key] = MessageAttributeValue::create($item);
            }
        }

        if (isset($input['MessageSystemAttributes'])) {
            $this->messageSystemAttributes = [];
            foreach ($input['MessageSystemAttributes'] as $key => $item) {
                $this->messageSystemAttributes[$key] = MessageSystemAttributeValue::create($item);
            }
        }
        $this->messageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->messageGroupId = $input['MessageGroupId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   QueueUrl?: string,
     *   MessageBody?: string,
     *   DelaySeconds?: null|int,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageSystemAttributes?: null|array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     *   '@region'?: string|null,
     * }|SendMessageRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDelaySeconds(): ?int
    {
        return $this->delaySeconds;
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    public function getMessageAttributes(): array
    {
        return $this->messageAttributes ?? [];
    }

    public function getMessageBody(): ?string
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

    public function getQueueUrl(): ?string
    {
        return $this->queueUrl;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'AmazonSQS.SendMessage',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDelaySeconds(?int $value): self
    {
        $this->delaySeconds = $value;

        return $this;
    }

    /**
     * @param array<string, MessageAttributeValue> $value
     */
    public function setMessageAttributes(array $value): self
    {
        $this->messageAttributes = $value;

        return $this;
    }

    public function setMessageBody(?string $value): self
    {
        $this->messageBody = $value;

        return $this;
    }

    public function setMessageDeduplicationId(?string $value): self
    {
        $this->messageDeduplicationId = $value;

        return $this;
    }

    public function setMessageGroupId(?string $value): self
    {
        $this->messageGroupId = $value;

        return $this;
    }

    /**
     * @param array<MessageSystemAttributeNameForSends::*, MessageSystemAttributeValue> $value
     */
    public function setMessageSystemAttributes(array $value): self
    {
        $this->messageSystemAttributes = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->queueUrl = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->queueUrl) {
            throw new InvalidArgument(\sprintf('Missing parameter "QueueUrl" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['QueueUrl'] = $v;
        if (null === $v = $this->messageBody) {
            throw new InvalidArgument(\sprintf('Missing parameter "MessageBody" for "%s". The value cannot be null.', __CLASS__));
        }
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
}
