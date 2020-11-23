<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\Sns\ValueObject\MessageAttributeValue;

final class PublishInput extends Input
{
    /**
     * The topic you want to publish to.
     *
     * @var string|null
     */
    private $TopicArn;

    /**
     * If you don't specify a value for the `TargetArn` parameter, you must specify a value for the `PhoneNumber` or
     * `TopicArn` parameters.
     *
     * @var string|null
     */
    private $TargetArn;

    /**
     * The phone number to which you want to deliver an SMS message. Use E.164 format.
     *
     * @var string|null
     */
    private $PhoneNumber;

    /**
     * The message you want to send.
     *
     * @required
     *
     * @var string|null
     */
    private $Message;

    /**
     * Optional parameter to be used as the "Subject" line when the message is delivered to email endpoints. This field will
     * also be included, if present, in the standard JSON messages delivered to other endpoints.
     *
     * @var string|null
     */
    private $Subject;

    /**
     * Set `MessageStructure` to `json` if you want to send a different message for each protocol. For example, using one
     * publish action, you can send a short message to your SMS subscribers and a longer message to your email subscribers.
     * If you set `MessageStructure` to `json`, the value of the `Message` parameter must:.
     *
     * @var string|null
     */
    private $MessageStructure;

    /**
     * Message attributes for Publish action.
     *
     * @var array<string, MessageAttributeValue>|null
     */
    private $MessageAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics. The `MessageDeduplicationId` can contain up to 128
     * alphanumeric characters (a-z, A-Z, 0-9) and punctuation `(!"#$%&amp;'()*+,-./:;&lt;=&gt;?@[\]^_`{|}~)`.
     *
     * @var string|null
     */
    private $MessageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) topics. The `MessageGroupId` can contain up to 128
     * alphanumeric characters (a-z, A-Z, 0-9) and punctuation `(!"#$%&amp;'()*+,-./:;&lt;=&gt;?@[\]^_`{|}~)`.
     *
     * @var string|null
     */
    private $MessageGroupId;

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
        $this->TopicArn = $input['TopicArn'] ?? null;
        $this->TargetArn = $input['TargetArn'] ?? null;
        $this->PhoneNumber = $input['PhoneNumber'] ?? null;
        $this->Message = $input['Message'] ?? null;
        $this->Subject = $input['Subject'] ?? null;
        $this->MessageStructure = $input['MessageStructure'] ?? null;

        if (isset($input['MessageAttributes'])) {
            $this->MessageAttributes = [];
            foreach ($input['MessageAttributes'] as $key => $item) {
                $this->MessageAttributes[$key] = MessageAttributeValue::create($item);
            }
        }
        $this->MessageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->MessageGroupId = $input['MessageGroupId'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    /**
     * @return array<string, MessageAttributeValue>
     */
    public function getMessageAttributes(): array
    {
        return $this->MessageAttributes ?? [];
    }

    public function getMessageDeduplicationId(): ?string
    {
        return $this->MessageDeduplicationId;
    }

    public function getMessageGroupId(): ?string
    {
        return $this->MessageGroupId;
    }

    public function getMessageStructure(): ?string
    {
        return $this->MessageStructure;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->PhoneNumber;
    }

    public function getSubject(): ?string
    {
        return $this->Subject;
    }

    public function getTargetArn(): ?string
    {
        return $this->TargetArn;
    }

    public function getTopicArn(): ?string
    {
        return $this->TopicArn;
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
        $this->Message = $value;

        return $this;
    }

    /**
     * @param array<string, MessageAttributeValue> $value
     */
    public function setMessageAttributes(array $value): self
    {
        $this->MessageAttributes = $value;

        return $this;
    }

    public function setMessageDeduplicationId(?string $value): self
    {
        $this->MessageDeduplicationId = $value;

        return $this;
    }

    public function setMessageGroupId(?string $value): self
    {
        $this->MessageGroupId = $value;

        return $this;
    }

    public function setMessageStructure(?string $value): self
    {
        $this->MessageStructure = $value;

        return $this;
    }

    public function setPhoneNumber(?string $value): self
    {
        $this->PhoneNumber = $value;

        return $this;
    }

    public function setSubject(?string $value): self
    {
        $this->Subject = $value;

        return $this;
    }

    public function setTargetArn(?string $value): self
    {
        $this->TargetArn = $value;

        return $this;
    }

    public function setTopicArn(?string $value): self
    {
        $this->TopicArn = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->TopicArn) {
            $payload['TopicArn'] = $v;
        }
        if (null !== $v = $this->TargetArn) {
            $payload['TargetArn'] = $v;
        }
        if (null !== $v = $this->PhoneNumber) {
            $payload['PhoneNumber'] = $v;
        }
        if (null === $v = $this->Message) {
            throw new InvalidArgument(sprintf('Missing parameter "Message" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Message'] = $v;
        if (null !== $v = $this->Subject) {
            $payload['Subject'] = $v;
        }
        if (null !== $v = $this->MessageStructure) {
            $payload['MessageStructure'] = $v;
        }
        if (null !== $v = $this->MessageAttributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["MessageAttributes.entry.$index.Name"] = $mapKey;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["MessageAttributes.entry.$index.Value.$bodyKey"] = $bodyValue;
                }
            }
        }
        if (null !== $v = $this->MessageDeduplicationId) {
            $payload['MessageDeduplicationId'] = $v;
        }
        if (null !== $v = $this->MessageGroupId) {
            $payload['MessageGroupId'] = $v;
        }

        return $payload;
    }
}
