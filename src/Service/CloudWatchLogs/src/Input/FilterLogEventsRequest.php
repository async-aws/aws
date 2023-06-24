<?php

namespace AsyncAws\CloudWatchLogs\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class FilterLogEventsRequest extends Input
{
    /**
     * The name of the log group to search.
     *
     * > You must include either `logGroupIdentifier` or `logGroupName`, but not both.
     *
     * @var string|null
     */
    private $logGroupName;

    /**
     * Specify either the name or ARN of the log group to view log events from. If the log group is in a source account and
     * you are using a monitoring account, you must use the log group ARN.
     *
     * > You must include either `logGroupIdentifier` or `logGroupName`, but not both.
     *
     * @var string|null
     */
    private $logGroupIdentifier;

    /**
     * Filters the results to only logs from the log streams in this list.
     *
     * If you specify a value for both `logStreamNamePrefix` and `logStreamNames`, the action returns an
     * `InvalidParameterException` error.
     *
     * @var string[]|null
     */
    private $logStreamNames;

    /**
     * Filters the results to include only events from log streams that have names starting with this prefix.
     *
     * If you specify a value for both `logStreamNamePrefix` and `logStreamNames`, but the value for `logStreamNamePrefix`
     * does not match any log stream names specified in `logStreamNames`, the action returns an `InvalidParameterException`
     * error.
     *
     * @var string|null
     */
    private $logStreamNamePrefix;

    /**
     * The start of the time range, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`. Events with a
     * timestamp before this time are not returned.
     *
     * @var string|null
     */
    private $startTime;

    /**
     * The end of the time range, expressed as the number of milliseconds after `Jan 1, 1970 00:00:00 UTC`. Events with a
     * timestamp later than this time are not returned.
     *
     * @var string|null
     */
    private $endTime;

    /**
     * The filter pattern to use. For more information, see Filter and Pattern Syntax [^1].
     *
     * If not provided, all the events are matched.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/logs/FilterAndPatternSyntax.html
     *
     * @var string|null
     */
    private $filterPattern;

    /**
     * The token for the next set of events to return. (You received this token from a previous call.).
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The maximum number of events to return. The default is 10,000 events.
     *
     * @var int|null
     */
    private $limit;

    /**
     * If the value is true, the operation attempts to provide responses that contain events from multiple log streams
     * within the log group, interleaved in a single response. If the value is false, all the matched log events in the
     * first log stream are searched first, then those in the next log stream, and so on.
     *
     * **Important** As of June 17, 2019, this parameter is ignored and the value is assumed to be true. The response from
     * this operation always interleaves events from multiple log streams within a log group.
     *
     * @var bool|null
     */
    private $interleaved;

    /**
     * Specify `true` to display the log event fields with all sensitive data unmasked and visible. The default is `false`.
     *
     * To use this operation with this parameter, you must be signed into an account with the `logs:Unmask` permission.
     *
     * @var bool|null
     */
    private $unmask;

    /**
     * @param array{
     *   logGroupName?: string,
     *   logGroupIdentifier?: string,
     *   logStreamNames?: string[],
     *   logStreamNamePrefix?: string,
     *   startTime?: string,
     *   endTime?: string,
     *   filterPattern?: string,
     *   nextToken?: string,
     *   limit?: int,
     *   interleaved?: bool,
     *   unmask?: bool,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->logGroupName = $input['logGroupName'] ?? null;
        $this->logGroupIdentifier = $input['logGroupIdentifier'] ?? null;
        $this->logStreamNames = $input['logStreamNames'] ?? null;
        $this->logStreamNamePrefix = $input['logStreamNamePrefix'] ?? null;
        $this->startTime = $input['startTime'] ?? null;
        $this->endTime = $input['endTime'] ?? null;
        $this->filterPattern = $input['filterPattern'] ?? null;
        $this->nextToken = $input['nextToken'] ?? null;
        $this->limit = $input['limit'] ?? null;
        $this->interleaved = $input['interleaved'] ?? null;
        $this->unmask = $input['unmask'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   logGroupName?: string,
     *   logGroupIdentifier?: string,
     *   logStreamNames?: string[],
     *   logStreamNamePrefix?: string,
     *   startTime?: string,
     *   endTime?: string,
     *   filterPattern?: string,
     *   nextToken?: string,
     *   limit?: int,
     *   interleaved?: bool,
     *   unmask?: bool,
     *   '@region'?: string|null,
     * }|FilterLogEventsRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndTime(): ?string
    {
        return $this->endTime;
    }

    public function getFilterPattern(): ?string
    {
        return $this->filterPattern;
    }

    /**
     * @deprecated
     */
    public function getInterleaved(): ?bool
    {
        @trigger_error(sprintf('The property "interleaved" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);

        return $this->interleaved;
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

    /**
     * @return string[]
     */
    public function getLogStreamNames(): array
    {
        return $this->logStreamNames ?? [];
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    public function getStartTime(): ?string
    {
        return $this->startTime;
    }

    public function getUnmask(): ?bool
    {
        return $this->unmask;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'Logs_20140328.FilterLogEvents',
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

    public function setEndTime(?string $value): self
    {
        $this->endTime = $value;

        return $this;
    }

    public function setFilterPattern(?string $value): self
    {
        $this->filterPattern = $value;

        return $this;
    }

    /**
     * @deprecated
     */
    public function setInterleaved(?bool $value): self
    {
        @trigger_error(sprintf('The property "interleaved" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
        $this->interleaved = $value;

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

    /**
     * @param string[] $value
     */
    public function setLogStreamNames(array $value): self
    {
        $this->logStreamNames = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    public function setStartTime(?string $value): self
    {
        $this->startTime = $value;

        return $this;
    }

    public function setUnmask(?bool $value): self
    {
        $this->unmask = $value;

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
        if (null !== $v = $this->logStreamNames) {
            $index = -1;
            $payload['logStreamNames'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['logStreamNames'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->logStreamNamePrefix) {
            $payload['logStreamNamePrefix'] = $v;
        }
        if (null !== $v = $this->startTime) {
            $payload['startTime'] = $v;
        }
        if (null !== $v = $this->endTime) {
            $payload['endTime'] = $v;
        }
        if (null !== $v = $this->filterPattern) {
            $payload['filterPattern'] = $v;
        }
        if (null !== $v = $this->nextToken) {
            $payload['nextToken'] = $v;
        }
        if (null !== $v = $this->limit) {
            $payload['limit'] = $v;
        }
        if (null !== $v = $this->interleaved) {
            @trigger_error(sprintf('The property "interleaved" of "%s" is deprecated by AWS.', __CLASS__), \E_USER_DEPRECATED);
            $payload['interleaved'] = (bool) $v;
        }
        if (null !== $v = $this->unmask) {
            $payload['unmask'] = (bool) $v;
        }

        return $payload;
    }
}
