<?php

namespace AsyncAws\Sqs\Input;

class ListQueuesRequest
{
    /**
     * A string to use for filtering the list results. Only those queues whose name begins with the specified string are
     * returned.
     *
     * @var string|null
     */
    private $QueueNamePrefix;

    /**
     * @param array{
     *   QueueNamePrefix?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->QueueNamePrefix = $input['QueueNamePrefix'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getQueueNamePrefix(): ?string
    {
        return $this->QueueNamePrefix;
    }

    public function requestBody(): array
    {
        $payload = ['Action' => 'ListQueues', 'Version' => '2012-11-05'];

        if (null !== $v = $this->QueueNamePrefix) {
            $payload['QueueNamePrefix'] = $v;
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

    public function setQueueNamePrefix(?string $value): self
    {
        $this->QueueNamePrefix = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
