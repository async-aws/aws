<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class GetQueueUrlRequest
{
    /**
     * The name of the queue whose URL must be fetched. Maximum 80 characters. Valid values: alphanumeric characters,
     * hyphens (`-`), and underscores (`_`).
     *
     * @required
     *
     * @var string|null
     */
    private $QueueName;

    /**
     * The AWS account ID of the account that created the queue.
     *
     * @var string|null
     */
    private $QueueOwnerAWSAccountId;

    /**
     * @param array{
     *   QueueName?: string,
     *   QueueOwnerAWSAccountId?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueName = $input['QueueName'] ?? null;
        $this->QueueOwnerAWSAccountId = $input['QueueOwnerAWSAccountId'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueName(): ?string
    {
        return $this->QueueName;
    }

    public function getQueueOwnerAWSAccountId(): ?string
    {
        return $this->QueueOwnerAWSAccountId;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'GetQueueUrl', 'Version' => '2012-11-05'];
        $payload['QueueName'] = $this->QueueName;

        if (null !== $v = $this->QueueOwnerAWSAccountId) {
            $payload['QueueOwnerAWSAccountId'] = $v;
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

    public function setQueueName(?string $value): self
    {
        $this->QueueName = $value;

        return $this;
    }

    public function setQueueOwnerAWSAccountId(?string $value): self
    {
        $this->QueueOwnerAWSAccountId = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['QueueName'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
