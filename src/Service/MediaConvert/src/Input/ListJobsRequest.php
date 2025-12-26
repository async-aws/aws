<?php

namespace AsyncAws\MediaConvert\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\MediaConvert\Enum\JobStatus;
use AsyncAws\MediaConvert\Enum\Order;

/**
 * You can send list jobs requests with an empty body. Optionally, you can filter the response by queue and/or job
 * status by specifying them in your request body. You can also optionally specify the maximum number, up to twenty, of
 * jobs to be returned.
 */
final class ListJobsRequest extends Input
{
    /**
     * Optional. Number of jobs, up to twenty, that will be returned at one time.
     *
     * @var int|null
     */
    private $maxResults;

    /**
     * Optional. Use this string, provided with the response to a previous request, to request the next batch of jobs.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * Optional. When you request lists of resources, you can specify whether they are sorted in ASCENDING or DESCENDING
     * order. Default varies by resource.
     *
     * @var Order::*|null
     */
    private $order;

    /**
     * Optional. Provide a queue name to get back only jobs from that queue.
     *
     * @var string|null
     */
    private $queue;

    /**
     * Optional. A job's status can be SUBMITTED, PROGRESSING, COMPLETE, CANCELED, or ERROR.
     *
     * @var JobStatus::*|null
     */
    private $status;

    /**
     * @param array{
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   Order?: Order::*|null,
     *   Queue?: string|null,
     *   Status?: JobStatus::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->maxResults = $input['MaxResults'] ?? null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->order = $input['Order'] ?? null;
        $this->queue = $input['Queue'] ?? null;
        $this->status = $input['Status'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   MaxResults?: int|null,
     *   NextToken?: string|null,
     *   Order?: Order::*|null,
     *   Queue?: string|null,
     *   Status?: JobStatus::*|null,
     *   '@region'?: string|null,
     * }|ListJobsRequest $input
     */
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

    /**
     * @return Order::*|null
     */
    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function getQueue(): ?string
    {
        return $this->queue;
    }

    /**
     * @return JobStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];
        if (null !== $this->maxResults) {
            $query['maxResults'] = (string) $this->maxResults;
        }
        if (null !== $this->nextToken) {
            $query['nextToken'] = $this->nextToken;
        }
        if (null !== $this->order) {
            if (!Order::exists($this->order)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "Order" for "%s". The value "%s" is not a valid "Order".', __CLASS__, $this->order));
            }
            $query['order'] = $this->order;
        }
        if (null !== $this->queue) {
            $query['queue'] = $this->queue;
        }
        if (null !== $this->status) {
            if (!JobStatus::exists($this->status)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "Status" for "%s". The value "%s" is not a valid "JobStatus".', __CLASS__, $this->status));
            }
            $query['status'] = $this->status;
        }

        // Prepare URI
        $uriString = '/2017-08-29/jobs';

        // Prepare Body
        $body = '';

        // Return the Request
        return new Request('GET', $uriString, $query, $headers, StreamFactory::create($body));
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

    /**
     * @param Order::*|null $value
     */
    public function setOrder(?string $value): self
    {
        $this->order = $value;

        return $this;
    }

    public function setQueue(?string $value): self
    {
        $this->queue = $value;

        return $this;
    }

    /**
     * @param JobStatus::*|null $value
     */
    public function setStatus(?string $value): self
    {
        $this->status = $value;

        return $this;
    }
}
