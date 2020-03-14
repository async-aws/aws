<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

class PublishInput
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
     * @var MessageAttributeValue[]
     */
    private $MessageAttributes;

    /**
     * @param array{
     *   TopicArn?: string,
     *   TargetArn?: string,
     *   PhoneNumber?: string,
     *   Message?: string,
     *   Subject?: string,
     *   MessageStructure?: string,
     *   MessageAttributes?: \AsyncAws\Sns\Input\MessageAttributeValue[],
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

        $this->MessageAttributes = [];
        foreach ($input['MessageAttributes'] ?? [] as $key => $item) {
            $this->MessageAttributes[$key] = MessageAttributeValue::create($item);
        }
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
     * @return MessageAttributeValue[]
     */
    public function getMessageAttributes(): array
    {
        return $this->MessageAttributes;
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

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($this->requestBody()));
    }

    public function setMessage(?string $value): self
    {
        $this->Message = $value;

        return $this;
    }

    /**
     * @param MessageAttributeValue[] $value
     */
    public function setMessageAttributes(array $value): self
    {
        $this->MessageAttributes = $value;

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

    public function validate(): void
    {
        if (null === $this->Message) {
            throw new InvalidArgument(sprintf('Missing parameter "Message" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        foreach ($this->MessageAttributes as $item) {
            $item->validate();
        }
    }

    private function requestBody(): string
    {
        $payload = ['Action' => 'Publish', 'Version' => '2010-03-31'];
        $indices = new \stdClass();
        if (null !== $v = $this->TopicArn) {
            $payload['TopicArn'] = $v;
        }
        if (null !== $v = $this->TargetArn) {
            $payload['TargetArn'] = $v;
        }
        if (null !== $v = $this->PhoneNumber) {
            $payload['PhoneNumber'] = $v;
        }
        $payload['Message'] = $this->Message;
        if (null !== $v = $this->Subject) {
            $payload['Subject'] = $v;
        }
        if (null !== $v = $this->MessageStructure) {
            $payload['MessageStructure'] = $v;
        }

        (static function (array $input) use (&$payload, $indices) {
            $indices->kd0379ae = 0;
            foreach ($input as $key => $value) {
                ++$indices->kd0379ae;
                $payload["MessageAttributes.entry.{$indices->kd0379ae}.Name"] = $key;

                if (null !== $value) {
                    (static function (MessageAttributeValue $input) use (&$payload, $indices) {
                        $payload["MessageAttributes.entry.{$indices->kd0379ae}.Value.DataType"] = $input->getDataType();
                        if (null !== $v = $input->getStringValue()) {
                            $payload["MessageAttributes.entry.{$indices->kd0379ae}.Value.StringValue"] = $v;
                        }
                        if (null !== $v = $input->getBinaryValue()) {
                            $payload["MessageAttributes.entry.{$indices->kd0379ae}.Value.BinaryValue"] = base64_encode($v);
                        }
                    })($value);
                }
            }
        })($this->MessageAttributes);

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }
}
