<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class ReceiveMessageRequest
{
    /**
     * The URL of the Amazon SQS queue from which messages are received.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * A list of attributes that need to be returned along with each message. These attributes include:.
     *
     * @var string[]
     */
    private $AttributeNames;

    /**
     * The name of the message attribute, where *N* is the index.
     *
     * @var string[]
     */
    private $MessageAttributeNames;

    /**
     * The maximum number of messages to return. Amazon SQS never returns more messages than this value (however, fewer
     * messages might be returned). Valid values: 1 to 10. Default: 1.
     *
     * @var int|null
     */
    private $MaxNumberOfMessages;

    /**
     * The duration (in seconds) that the received messages are hidden from subsequent retrieve requests after being
     * retrieved by a `ReceiveMessage` request.
     *
     * @var int|null
     */
    private $VisibilityTimeout;

    /**
     * The duration (in seconds) for which the call waits for a message to arrive in the queue before returning. If a
     * message is available, the call returns sooner than `WaitTimeSeconds`. If no messages are available and the wait time
     * expires, the call returns successfully with an empty list of messages.
     *
     * @var int|null
     */
    private $WaitTimeSeconds;

    /**
     * This parameter applies only to FIFO (first-in-first-out) queues.
     *
     * @var string|null
     */
    private $ReceiveRequestAttemptId;

    /**
     * @param array{
     *   QueueUrl: string,
     *   AttributeNames?: string[],
     *   MessageAttributeNames?: string[],
     *   MaxNumberOfMessages?: int,
     *   VisibilityTimeout?: int,
     *   WaitTimeSeconds?: int,
     *   ReceiveRequestAttemptId?: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
        $this->AttributeNames = $input['AttributeNames'] ?? [];
        $this->MessageAttributeNames = $input['MessageAttributeNames'] ?? [];
        $this->MaxNumberOfMessages = $input['MaxNumberOfMessages'] ?? null;
        $this->VisibilityTimeout = $input['VisibilityTimeout'] ?? null;
        $this->WaitTimeSeconds = $input['WaitTimeSeconds'] ?? null;
        $this->ReceiveRequestAttemptId = $input['ReceiveRequestAttemptId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeNames(): array
    {
        return $this->AttributeNames;
    }

    public function getMaxNumberOfMessages(): ?int
    {
        return $this->MaxNumberOfMessages;
    }

    public function getMessageAttributeNames(): array
    {
        return $this->MessageAttributeNames;
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
    }

    public function getReceiveRequestAttemptId(): ?string
    {
        return $this->ReceiveRequestAttemptId;
    }

    public function getVisibilityTimeout(): ?int
    {
        return $this->VisibilityTimeout;
    }

    public function getWaitTimeSeconds(): ?int
    {
        return $this->WaitTimeSeconds;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'ReceiveMessage', 'Version' => '2012-11-05'];
        if (null !== $this->QueueUrl) {
            $payload['QueueUrl'] = $this->QueueUrl;
        }
        if (null !== $this->AttributeNames) {
            $payload['AttributeNames'] = $this->AttributeNames;
        }
        if (null !== $this->MessageAttributeNames) {
            $payload['MessageAttributeNames'] = $this->MessageAttributeNames;
        }
        if (null !== $this->MaxNumberOfMessages) {
            $payload['MaxNumberOfMessages'] = $this->MaxNumberOfMessages;
        }
        if (null !== $this->VisibilityTimeout) {
            $payload['VisibilityTimeout'] = $this->VisibilityTimeout;
        }
        if (null !== $this->WaitTimeSeconds) {
            $payload['WaitTimeSeconds'] = $this->WaitTimeSeconds;
        }
        if (null !== $this->ReceiveRequestAttemptId) {
            $payload['ReceiveRequestAttemptId'] = $this->ReceiveRequestAttemptId;
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

    public function setAttributeNames(array $value): self
    {
        $this->AttributeNames = $value;

        return $this;
    }

    public function setMaxNumberOfMessages(?int $value): self
    {
        $this->MaxNumberOfMessages = $value;

        return $this;
    }

    public function setMessageAttributeNames(array $value): self
    {
        $this->MessageAttributeNames = $value;

        return $this;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    public function setReceiveRequestAttemptId(?string $value): self
    {
        $this->ReceiveRequestAttemptId = $value;

        return $this;
    }

    public function setVisibilityTimeout(?int $value): self
    {
        $this->VisibilityTimeout = $value;

        return $this;
    }

    public function setWaitTimeSeconds(?int $value): self
    {
        $this->WaitTimeSeconds = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['QueueUrl'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
