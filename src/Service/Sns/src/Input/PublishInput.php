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
     * @var string|null
     */
    private $phoneNumber;

    /**
     * The message you want to send.
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
     * @var string|null
     */
    private $subject;

    /**
     * Set `MessageStructure` to `json` if you want to send a different message for each protocol. For example, using one
     * publish action, you can send a short message to your SMS subscribers and a longer message to your email subscribers.
     * If you set `MessageStructure` to `json`, the value of the `Message` parameter must:.
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
     * alphanumeric characters (a-z, A-Z, 0-9) and punctuation `(!"#$%&amp;'()*+,-./:;&lt;=&gt;?@[\]^_`{|}~)`.
     *
     * @var string|null
     */
    private $messageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics. The `MessageGroupId` can contain up to 128
     * alphanumeric characters (a-z, A-Z, 0-9) and punctuation `(!"#$%&amp;'()*+,-./:;&lt;=&gt;?@[\]^_`{|}~)`.
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
     *   MessageAttributes?: array<string, MessageAttributeValue>,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     *   @region?: string,
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
