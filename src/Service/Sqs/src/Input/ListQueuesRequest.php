<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListQueuesRequest extends Input
{
    /**
     * A string to use for filtering the list results. Only those queues whose name begins with the specified string are
     * returned.
     *
     * @var string|null
     */
    private $queueNamePrefix;

    /**
     * Pagination token to request the next set of results.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Maximum number of results to include in the response. Value range is 1 to 1000. You must set `MaxResults` to receive
     * a value for `NextToken` in the response.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * @param array{
     *   QueueNamePrefix?: string,
     *   NextToken?: string,
     *   MaxResults?: int,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->queueNamePrefix = $input['QueueNamePrefix'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->maxResults = $input['MaxResults'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMaxResults(): ?int
    {
        return $this->maxResults;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getQueueNamePrefix(): ?string
    {
        return $this->queueNamePrefix;
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

        // Prepare Body
        $body = http_build_query(['Action' => 'ListQueues', 'Version' => '2012-11-05'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setMaxResults(?int $value): self
    {
        $this->maxResults = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setQueueNamePrefix(?string $value): self
    {
        $this->queueNamePrefix = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->queueNamePrefix) {
            $payload['QueueNamePrefix'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->maxResults) {
            $payload['MaxResults'] = $v;
        }

        return $payload;
    }
}
