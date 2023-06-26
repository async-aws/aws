<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sns\ValueObject\MessageAttributeValue;

/**
 * Input for Publish action.
 */
final class PublishInput extends Input
{
    /**
     * The topic you want to publish to.
     *
     * If you don't specify a value for the `TopicArn` parameter, you must specify a value for the `PhoneNumber` or
     * `TargetArn` parameters.
     *
     * @var string|null
     */
    private $topicArn;

    /**
     * If you don't specify a value for the `TargetArn` parameter, you must specify a value for the `PhoneNumber` or
     * `TopicArn` parameters.
     *
     * @var string|null
     */
    private $targetArn;

    /**
     * The phone number to which you want to deliver an SMS message. Use E.164 format.
     *
     * If you don't specify a value for the `PhoneNumber` parameter, you must specify a value for the `TargetArn` or
     * `TopicArn` parameters.
     *
     * @var string|null
     */
    private $phoneNumber;

    /**
     * The message you want to send.
     *
     * If you are publishing to a topic and you want to send the same message to all transport protocols, include the text
     * of the message as a String value. If you want to send different messages for each transport protocol, set the value
     * of the `MessageStructure` parameter to `json` and use a JSON object for the `Message` parameter.
     *
     * Constraints:
     *
     * - With the exception of SMS, messages must be UTF-8 encoded strings and at most 256 KB in size (262,144 bytes, not
     *   262,144 characters).
     * - For SMS, each message can contain up to 140 characters. This character limit depends on the encoding schema. For
     *   example, an SMS message can contain 160 GSM characters, 140 ASCII characters, or 70 UCS-2 characters.
     *
     *   If you publish a message that exceeds this size limit, Amazon SNS sends the message as multiple messages, each
     *   fitting within the size limit. Messages aren't truncated mid-word but are cut off at whole-word boundaries.
     *
     *   The total size limit for a single SMS `Publish` action is 1,600 characters.
     *
     * JSON-specific constraints:
     *
     * - Keys in the JSON object that correspond to supported transport protocols must have simple JSON string values.
     * - The values will be parsed (unescaped) before they are used in outgoing messages.
     * - Outbound notifications are JSON encoded (meaning that the characters will be reescaped for sending).
     * - Values have a minimum length of 0 (the empty string, "", is allowed).
     * - Values have a maximum length bounded by the overall message size (so, including multiple protocols may limit
     *   message sizes).
     * - Non-string values will cause the key to be ignored.
     * - Keys that do not correspond to supported transport protocols are ignored.
     * - Duplicate keys are not allowed.
     * - Failure to parse or validate any key or value in the message will cause the `Publish` call to return an error (no
     *   partial delivery).
     *
     * @required
     *
     * @var string|null
     */
    private $message;

    /**
     * Optional parameter to be used as the "Subject" line when the message is delivered to email endpoints. This field will
     * also be included, if present, in the standard JSON messages delivered to other endpoints.
     *
     * Constraints: Subjects must be ASCII text that begins with a letter, number, or punctuation mark; must not include
     * line breaks or control characters; and must be less than 100 characters long.
     *
     * @var string|null
     */
    private $subject;

    /**
     * Set `MessageStructure` to `json` if you want to send a different message for each protocol. For example, using one
     * publish action, you can send a short message to your SMS subscribers and a longer message to your email subscribers.
     * If you set `MessageStructure` to `json`, the value of the `Message` parameter must:.
     *
     * - be a syntactically valid JSON object; and
     * - contain at least a top-level JSON key of "default" with a value that is a string.
     *
     * You can define other top-level keys that define the message you want to send to a specific transport protocol (e.g.,
     * "http").
     *
     * Valid value: `json`
     *
     * @var string|null
     */
    private $messageStructure;

    /**
     * Message attributes for Publish action.
     *
     * @var array<string, MessageAttributeValue>|null
     */
    private $messageAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics. The `MessageDeduplicationId` can contain up to 128
     * alphanumeric characters `(a-z, A-Z, 0-9)` and punctuation `(!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~)`.
     *
     * Every message must have a unique `MessageDeduplicationId`, which is a token used for deduplication of sent messages.
     * If a message with a particular `MessageDeduplicationId` is sent successfully, any message sent with the same
     * `MessageDeduplicationId` during the 5-minute deduplication interval is treated as a duplicate.
     *
     * If the topic has `ContentBasedDeduplication` set, the system generates a `MessageDeduplicationId` based on the
     * contents of the message. Your `MessageDeduplicationId` overrides the generated one.
     *
     * @var string|null
     */
    private $messageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics. The `MessageGroupId` can contain up to 128
     * alphanumeric characters `(a-z, A-Z, 0-9)` and punctuation `(!"#$%&'()*+,-./:;<=>?@[\]^_`{|}~)`.
     *
     * The `MessageGroupId` is a tag that specifies that a message belongs to a specific message group. Messages that belong
     * to the same message group are processed in a FIFO manner (however, messages in different message groups might be
     * processed out of order). Every message must include a `MessageGroupId`.
     *
     * @var string|null
     */
    private $messageGroupId;

    /**
     * @param array{
     *   TopicArn?: string,
     *   TargetArn?: string,
     *   PhoneNumber?: string,
     *   Message?: string,
     *   Subject?: string,
     *   MessageStructure?: string,
     *   MessageAttributes?: array<string, MessageAttributeValue|array>,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->topicArn = $input['TopicArn'] ?? null;
        $this->targetArn = $input['TargetArn'] ?? null;
        $this->phoneNumber = $input['PhoneNumber'] ?? null;
        $this->message = $input['Message'] ?? null;
        $this->subject = $input['Subject'] ?? null;
        $this->messageStructure = $input['MessageStructure'] ?? null;

        if (isset($input['MessageAttributes'])) {
            $this->messageAttributes = [];
            foreach ($input['MessageAttributes'] as $key => $item) {
                $this->messageAttributes[$key] = MessageAttributeValue::create($item);
            }
        }
        $this->messageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->messageGroupId = $input['MessageGroupId'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TopicArn?: string,
     *   TargetArn?: string,
     *   PhoneNumber?: string,
     *   Message?: string,
     *   Subject?: string,
     *   MessageStructure?: string,
     *   MessageAttributes?: array<string, MessageAttributeValue|array>,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     *   '@region'?: string|null,
     * }|PublishInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessage(): ?string
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getTargetArn(): ?string
    {
        return $this->targetArn;
    }

    public function getTopicArn(): ?string
    {
        return $this->topicArn;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'Publish', 'Version' => '2010-03-31'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setMessage(?string $value): self
    {
        $this->message = $value;

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

    public function setMessageStructure(?string $value): self
    {
        $this->messageStructure = $value;

        return $this;
    }

    public function setPhoneNumber(?string $value): self
    {
        $this->phoneNumber = $value;

        return $this;
    }

    public function setSubject(?string $value): self
    {
        $this->subject = $value;

        return $this;
    }

    public function setTargetArn(?string $value): self
    {
        $this->targetArn = $value;

        return $this;
    }

    public function setTopicArn(?string $value): self
    {
        $this->topicArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->topicArn) {
            $payload['TopicArn'] = $v;
        }
        if (null !== $v = $this->targetArn) {
            $payload['TargetArn'] = $v;
        }
        if (null !== $v = $this->phoneNumber) {
            $payload['PhoneNumber'] = $v;
        }
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
