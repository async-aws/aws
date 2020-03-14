<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

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

    public function setQueueNamePrefix(?string $value): self
    {
        $this->QueueNamePrefix = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }

    private function requestBody(): string
    {
        $payload = ['Action' => 'ListQueues', 'Version' => '2012-11-05'];
        if (null !== $v = $this->QueueNamePrefix) {
            $payload['QueueNamePrefix'] = $v;
        }

        return http_build_query($payload, '', '&', \PHP_QUERY_RFC1738);
    }
}
