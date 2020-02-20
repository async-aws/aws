<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class SendMessageRequest
{
    /**
     * The URL of the Amazon SQS queue to which a message is sent.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * The message to send. The maximum string size is 256 KB.
     *
     * @required
     *
     * @var string|null
     */
    private $MessageBody;

    /**
     * The length of time, in seconds, for which to delay a specific message. Valid values: 0 to 900. Maximum: 15 minutes.
     * Messages with a positive `DelaySeconds` value become available for processing after the delay period is finished. If
     * you don't specify a value, the default value for the queue applies.
     *
     * @var int|null
     */
    private $DelaySeconds;

    /**
     * Each message attribute consists of a `Name`, `Type`, and `Value`. For more information, see Amazon SQS Message
     * Attributes in the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-attributes.html
     *
     * @var MessageAttributeValue[]
     */
    private $MessageAttributes;

    /**
     * The message system attribute to send. Each message system attribute consists of a `Name`, `Type`, and `Value`.
     *
     * @var MessageSystemAttributeValue[]
     */
    private $MessageSystemAttributes;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * @var string|null
     */
    private $MessageDeduplicationId;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * @var string|null
     */
    private $MessageGroupId;

    /**
     * @param array{
     *   QueueUrl?: string,
     *   MessageBody?: string,
     *   DelaySeconds?: int,
     *   MessageAttributes?: array,
     *   MessageSystemAttributes?: array,
     *   MessageDeduplicationId?: string,
     *   MessageGroupId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->MessageBody = $input['MessageBody'] ?? null;
        $this->DelaySeconds = $input['DelaySeconds'] ?? null;

        $this->MessageAttributes = [];
        foreach ($input['MessageAttributes'] ?? [] as $key => $item) {
            $this->MessageAttributes[$key] = MessageAttributeValue::create($item);
        }

        $this->MessageSystemAttributes = [];
        foreach ($input['MessageSystemAttributes'] ?? [] as $key => $item) {
            $this->MessageSystemAttributes[$key] = MessageSystemAttributeValue::create($item);
        }
        $this->MessageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->MessageGroupId = $input['MessageGroupId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDelaySeconds(): ?int
    {
        return $this->DelaySeconds;
    }

    public function getMessageAttributes(): array
    {
        return $this->MessageAttributes;
    }

    public function getMessageBody(): ?string
    {
        return $this->MessageBody;
    }

    public function getMessageDeduplicationId(): ?string
    {
        return $this->MessageDeduplicationId;
    }

    public function getMessageGroupId(): ?string
    {
        return $this->MessageGroupId;
    }

    public function getMessageSystemAttributes(): array
    {
        return $this->MessageSystemAttributes;
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'SendMessage', 'Version' => '2012-11-05'];
        if (null !== $this->QueueUrl) {
            $payload['QueueUrl'] = $this->QueueUrl;
        }
        if (null !== $this->MessageBody) {
            $payload['MessageBody'] = $this->MessageBody;
        }
        if (null !== $this->DelaySeconds) {
            $payload['DelaySeconds'] = $this->DelaySeconds;
        }
        if (null !== $this->MessageAttributes) {
            $payload['MessageAttribute'] = $this->MessageAttributes;
        }
        if (null !== $this->MessageSystemAttributes) {
            $payload['MessageSystemAttribute'] = $this->MessageSystemAttributes;
        }
        if (null !== $this->MessageDeduplicationId) {
            $payload['MessageDeduplicationId'] = $this->MessageDeduplicationId;
        }
        if (null !== $this->MessageGroupId) {
            $payload['MessageGroupId'] = $this->MessageGroupId;
        }

        return $payload;
    }

    public function requestHeaders(): array
    {
        $headers = [];

        return $headers;
    }

    public function requestQuery(): array
    {
        $query = [];

        return $query;
    }

    public function requestUri(): string
    {
        return '/';
    }

    public function setDelaySeconds(?int $value): self
    {
        $this->DelaySeconds = $value;

        return $this;
    }

    public function setMessageAttributes(array $value): self
    {
        $this->MessageAttributes = $value;

        return $this;
    }

    public function setMessageBody(?string $value): self
    {
        $this->MessageBody = $value;

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

    public function setMessageSystemAttributes(array $value): self
    {
        $this->MessageSystemAttributes = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['QueueUrl', 'MessageBody'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        foreach ($this->MessageAttributes as $item) {
            $item->validate();
        }
        foreach ($this->MessageSystemAttributes as $item) {
            $item->validate();
        }
    }
}
