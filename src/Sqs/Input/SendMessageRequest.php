<?php

namespace AsyncAws\Sqs\Input;

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
        $this->QueueUrl = $input["QueueUrl"] ?? null;
        $this->MessageBody = $input["MessageBody"] ?? null;
        $this->DelaySeconds = $input["DelaySeconds"] ?? null;
        $this->MessageAttributes = $input["MessageAttributes"] ?? null;
        $this->MessageSystemAttributes = $input["MessageSystemAttributes"] ?? null;
        $this->MessageDeduplicationId = $input["MessageDeduplicationId"] ?? null;
        $this->MessageGroupId = $input["MessageGroupId"] ?? null;
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

    public function requestUri(): array
    {
        $uri = [];

        return $uri;
    }
}
