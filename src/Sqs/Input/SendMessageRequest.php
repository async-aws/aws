<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class SendMessageRequest
{
    /**
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * @required
     *
     * @var string|null
     */
    private $MessageBody;

    /**
     * @var int|null
     */
    private $DelaySeconds;

    /**
     * @var array|null
     */
    private $MessageAttributes;

    /**
     * @var array|null
     */
    private $MessageSystemAttributes;

    /**
     * @var string|null
     */
    private $MessageDeduplicationId;

    /**
     * @var string|null
     */
    private $MessageGroupId;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   QueueUrl: string,
     *   MessageBody: string,
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
        $this->MessageAttributes = $input['MessageAttributes'] ?? null;
        $this->MessageSystemAttributes = $input['MessageSystemAttributes'] ?? null;
        $this->MessageDeduplicationId = $input['MessageDeduplicationId'] ?? null;
        $this->MessageGroupId = $input['MessageGroupId'] ?? null;
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
    }

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

        return $this;
    }

    public function getMessageBody(): ?string
    {
        return $this->MessageBody;
    }

    public function setMessageBody(?string $value): self
    {
        $this->MessageBody = $value;

        return $this;
    }

    public function getDelaySeconds(): ?int
    {
        return $this->DelaySeconds;
    }

    public function setDelaySeconds(?int $value): self
    {
        $this->DelaySeconds = $value;

        return $this;
    }

    public function getMessageAttributes(): ?array
    {
        return $this->MessageAttributes;
    }

    public function setMessageAttributes(?array $value): self
    {
        $this->MessageAttributes = $value;

        return $this;
    }

    public function getMessageSystemAttributes(): ?array
    {
        return $this->MessageSystemAttributes;
    }

    public function setMessageSystemAttributes(?array $value): self
    {
        $this->MessageSystemAttributes = $value;

        return $this;
    }

    public function getMessageDeduplicationId(): ?string
    {
        return $this->MessageDeduplicationId;
    }

    public function setMessageDeduplicationId(?string $value): self
    {
        $this->MessageDeduplicationId = $value;

        return $this;
    }

    public function getMessageGroupId(): ?string
    {
        return $this->MessageGroupId;
    }

    public function setMessageGroupId(?string $value): self
    {
        $this->MessageGroupId = $value;

        return $this;
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

    public function requestBody(): array
    {
        $payload = ['Action' => 'SendMessage'];
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

    public function requestUri(): string
    {
        return '/';
    }

    public function validate(): void
    {
        foreach (['QueueUrl', 'MessageBody'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
