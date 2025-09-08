<?php

namespace AsyncAws\CloudWatchLogs\Input;

use AsyncAws\CloudWatchLogs\Enum\OrderBy;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class DescribeLogStreamsRequest extends Input
{
    /**
     * The name of the log group.
     *
     * > You must include either `logGroupIdentifier` or `logGroupName`, but not both.
     *
     * @var string|null
     */
    private $logGroupName;

    /**
     * Specify either the name or ARN of the log group to view. If the log group is in a source account and you are using a
     * monitoring account, you must use the log group ARN.
     *
     * > You must include either `logGroupIdentifier` or `logGroupName`, but not both.
     *
     * @var string|null
     */
    private $logGroupIdentifier;

    /**
     * The prefix to match.
     *
     * If `orderBy` is `LastEventTime`, you cannot specify this parameter.
     *
     * @var string|null
     */
    private $logStreamNamePrefix;

    /**
     * If the value is `LogStreamName`, the results are ordered by log stream name. If the value is `LastEventTime`, the
     * results are ordered by the event time. The default value is `LogStreamName`.
     *
     * If you order the results by event time, you cannot specify the `logStreamNamePrefix` parameter.
     *
     * `lastEventTimestamp` represents the time of the most recent log event in the log stream in CloudWatch Logs. This
     * number is expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`. `lastEventTimestamp` updates on
     * an eventual consistency basis. It typically updates in less than an hour from ingestion, but in rare situations might
     * take longer.
     *
     * @var OrderBy::*|null
     */
    private $orderBy;

    /**
     * If the value is true, results are returned in descending order. If the value is to false, results are returned in
     * ascending order. The default value is false.
     *
     * @var bool|null
     */
    private $descending;

    /**
     * The token for the next set of items to return. (You received this token from a previous call.).
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of items returned. If you don't specify a value, the default is up to 50 items.
     *
     * @var int|null
     */
    private $limit;

    /**
     * @param array{
     *   logGroupName?: string|null,
     *   logGroupIdentifier?: string|null,
     *   logStreamNamePrefix?: string|null,
     *   orderBy?: OrderBy::*|null,
     *   descending?: bool|null,
     *   nextToken?: string|null,
     *   limit?: int|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
        $this->logGroupIdentifier = $input['logGroupIdentifier'] ?? null;
        $this->logStreamNamePrefix = $input['logStreamNamePrefix'] ?? null;
        $this->orderBy = $input['orderBy'] ?? null;
        $this->descending = $input['descending'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->limit = $input['limit'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   logGroupName?: string|null,
     *   logGroupIdentifier?: string|null,
     *   logStreamNamePrefix?: string|null,
     *   orderBy?: OrderBy::*|null,
     *   descending?: bool|null,
     *   nextToken?: string|null,
     *   limit?: int|null,
     *   '@region'?: string|null,
     * }|DescribeLogStreamsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDescending(): ?bool
    {
        return $this->descending;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getLogGroupIdentifier(): ?string
    {
        return $this->logGroupIdentifier;
    }

    public function getLogGroupName(): ?string
    {
        return $this->logGroupName;
    }

    public function getLogStreamNamePrefix(): ?string
    {
        return $this->logStreamNamePrefix;
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return OrderBy::*|null
     */
    public function getOrderBy(): ?string
    {
        return $this->orderBy;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Logs_20140328.DescribeLogStreams',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setDescending(?bool $value): self
    {
        $this->descending = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setLogGroupIdentifier(?string $value): self
    {
        $this->logGroupIdentifier = $value;

        return $this;
    }

    public function setLogGroupName(?string $value): self
    {
        $this->logGroupName = $value;

        return $this;
    }

    public function setLogStreamNamePrefix(?string $value): self
    {
        $this->logStreamNamePrefix = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param OrderBy::*|null $value
     */
    public function setOrderBy(?string $value): self
    {
        $this->orderBy = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->logGroupName) {
            $payload['logGroupName'] = $v;
        }
        if (null !== $v = $this->logGroupIdentifier) {
            $payload['logGroupIdentifier'] = $v;
        }
        if (null !== $v = $this->logStreamNamePrefix) {
            $payload['logStreamNamePrefix'] = $v;
        }
        if (null !== $v = $this->orderBy) {
            if (!OrderBy::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "orderBy" for "%s". The value "%s" is not a valid "OrderBy".', __CLASS__, $v));
            }
            $payload['orderBy'] = $v;
        }
        if (null !== $v = $this->descending) {
            $payload['descending'] = (bool) $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }
        if (null !== $v = $this->limit) {
            $payload['limit'] = $v;
        }

        return $payload;
    }
}
