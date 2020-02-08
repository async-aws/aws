<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class PurgeQueueRequest
{
    /**
     * The URL of the queue from which the `PurgeQueue` action deletes messages.
     *
     * @required
     *
     * @var string|null
     */
    private $QueueUrl;

    /**
     * @param array{
     *   QueueUrl?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueUrl = $input['QueueUrl'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueUrl(): ?string
    {
        return $this->QueueUrl;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'PurgeQueue', 'Version' => '2012-11-05'];
        if (null !== $this->QueueUrl) {
            $payload['QueueUrl'] = $this->QueueUrl;
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

    public function setQueueUrl(?string $value): self
    {
        $this->QueueUrl = $value;

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
