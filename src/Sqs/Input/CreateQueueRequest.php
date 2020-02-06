<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class CreateQueueRequest
{
    /**
     * The name of the new queue. The following limits apply to this name:.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueName;

    /**
     * A map of attributes with their corresponding values.
     *
     * @var array|null
     */
    private $Attributes;

    /**
     * Add cost allocation tags to the specified Amazon SQS queue. For an overview, see Tagging Your Amazon SQS Queues in
     * the *Amazon Simple Queue Service Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-queue-tags.html
     *
     * @var array|null
     */
    private $tags;

    /**
     * @param array{
     *   QueueName: string,
     *   Attributes?: array,
     *   tags?: array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->QueueName = $input['QueueName'] ?? null;
        $this->Attributes = $input['Attributes'] ?? null;
        $this->tags = $input['tags'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributes(): ?array
    {
        return $this->Attributes;
    }

    public function getQueueName(): ?string
    {
        return $this->QueueName;
    }

    public function gettags(): ?array
    {
        return $this->tags;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'CreateQueue', 'Version' => '2012-11-05'];
        if (null !== $this->QueueName) {
            $payload['QueueName'] = $this->QueueName;
        }
        if (null !== $this->Attributes) {
            $payload['Attribute'] = $this->Attributes;
        }
        if (null !== $this->tags) {
            $payload['Tag'] = $this->tags;
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

    public function setAttributes(?array $value): self
    {
        $this->Attributes = $value;

        return $this;
    }

    public function setQueueName(?string $value): self
    {
        $this->QueueName = $value;

        return $this;
    }

    public function settags(?array $value): self
    {
        $this->tags = $value;

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
