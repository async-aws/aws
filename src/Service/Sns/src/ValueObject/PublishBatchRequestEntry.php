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
     *
     * > The `Ids` of a batch request must be unique within a request.
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
    private $message;

    /**
     * The subject of the batch message.
     *
     * @var string|null
     */
    private $subject;

    /**
     * Set `MessageStructure` to `json` if you want to send a different message for each protocol. For example, using one
     * publish action, you can send a short message to your SMS subscribers and a longer message to your email subscribers.
     * If you set `MessageStructure` to `json`, the value of the `Message` parameter must:
     *
     * - be a syntactically valid JSON object; and
     * - contain at least a top-level JSON key of "default" with a value that is a string.
     *
     * You can define other top-level keys that define the message you want to send to a specific transport protocol (e.g.
     * http).
     *
     * @var string|null
     */
    private $messageStructure;

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SNS message
     * attributes [^1] in the Amazon SNS Developer Guide.
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/sns-message-attributes.html
     *
     * @var array<string, MessageAttributeValue>|null
     */
    private $messageAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics.
     *
     * - This parameter applies only to FIFO (first-in-first-out) topics. The `MessageDeduplicationId` can contain up to 128
     *   alphanumeric characters `(a-z, A-Z, 0-9)` and punctuation `(!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~)`.
     * - Every message must have a unique `MessageDeduplicationId`, which is a token used for deduplication of sent messages
     *   within the 5 minute minimum deduplication interval.
     * - The scope of deduplication depends on the `FifoThroughputScope` attribute, when set to `Topic` the message
     *   deduplication scope is across the entire topic, when set to `MessageGroup` the message deduplication scope is
     *   within each individual message group.
     * - If a message with a particular `MessageDeduplicationId` is sent successfully, subsequent messages within the
     *   deduplication scope and interval, with the same `MessageDeduplicationId`, are accepted successfully but aren't
     *   delivered.
     * - Every message must have a unique `MessageDeduplicationId`.
     *
     *   - You may provide a `MessageDeduplicationId` explicitly.
     *   - If you aren't able to provide a `MessageDeduplicationId` and you enable `ContentBasedDeduplication` for your
     *     topic, Amazon SNS uses a SHA-256 hash to generate the `MessageDeduplicationId` using the body of the message (but
     *     not the attributes of the message).
     *   - If you don't provide a `MessageDeduplicationId` and the topic doesn't have `ContentBasedDeduplication` set, the
     *     action fails with an error.
     *   - If the topic has a `ContentBasedDeduplication` set, your `MessageDeduplicationId` overrides the generated one.
     *
     * - When `ContentBasedDeduplication` is in effect, messages with identical content sent within the deduplication scope
     *   and interval are treated as duplicates and only one copy of the message is delivered.
     * - If you send one message with `ContentBasedDeduplication` enabled, and then another message with a
     *   `MessageDeduplicationId` that is the same as the one generated for the first `MessageDeduplicationId`, the two
     *   messages are treated as duplicates, within the deduplication scope and interval, and only one copy of the message
     *   is delivered.
     *
     * > The `MessageDeduplicationId` is available to the consumer of the message (this can be useful for troubleshooting
     * > delivery issues).
     * >
     * > If a message is sent successfully but the acknowledgement is lost and the message is resent with the same
     * > `MessageDeduplicationId` after the deduplication interval, Amazon SNS can't detect duplicate messages.
     * >
     * > Amazon SNS continues to keep track of the message deduplication ID even after the message is received and deleted.
     *
     * @var string|null
     */
    private $messageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics.
     *
     * The tag that specifies that a message belongs to a specific message group. Messages that belong to the same message
     * group are processed in a FIFO manner (however, messages in different message groups might be processed out of order).
     * To interleave multiple ordered streams within a single topic, use `MessageGroupId` values (for example, session data
     * for multiple users). In this scenario, multiple consumers can process the topic, but the session data of each user is
     * processed in a FIFO fashion.
     *
     * You must associate a non-empty `MessageGroupId` with a message. If you don't provide a `MessageGroupId`, the action
     * fails.
     *
     * The length of `MessageGroupId` is 128 characters.
     *
     * `MessageGroupId` can contain alphanumeric characters `(a-z, A-Z, 0-9)` and punctuation
     * `(!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~)`.
     *
     * ! `MessageGroupId` is required for FIFO topics. You can't use it for standard topics.
     *
     * @var string|null
     */
    private $messageGroupId;

    /**
     * @param array{
     *   Id: string,
     *   Message: string,
     *   Subject?: null|string,
     *   MessageStructure?: null|string,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->id = $input['Id'] ?? $this->throwException(new InvalidArgument('Missing required field "Id".'));
        $this->message = $input['Message'] ?? $this->throwException(new InvalidArgument('Missing required field "Message".'));
        $this->subject = $input['Subject'] ?? null;
        $this->messageStructure = $input['MessageStructure'] ?? null;
        $this->messageAttributes = isset($input['MessageAttributes']) ? array_map([MessageAttributeValue::class, 'create'], $input['MessageAttributes']) : null;
        $this->messageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->messageGroupId = $input['MessageGroupId'] ?? null;
    }

    /**
     * @param array{
     *   Id: string,
     *   Message: string,
     *   Subject?: null|string,
     *   MessageStructure?: null|string,
     *   MessageAttributes?: null|array<string, MessageAttributeValue|array>,
     *   MessageDeduplicationId?: null|string,
     *   MessageGroupId?: null|string,
     * }|PublishBatchRequestEntry $input
     */
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
        $v = $this->id;
        $payload['Id'] = $v;
        $v = $this->message;
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
