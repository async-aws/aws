<?php

namespace AsyncAws\CloudWatch\Input;

use AsyncAws\CloudWatch\Enum\ScanBy;
use AsyncAws\CloudWatch\ValueObject\LabelOptions;
use AsyncAws\CloudWatch\ValueObject\Metric;
use AsyncAws\CloudWatch\ValueObject\MetricDataQuery;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetMetricDataInput extends Input
{
    /**
     * The metric queries to be returned. A single `GetMetricData` call can include as many as 500 `MetricDataQuery`
     * structures. Each of these structures can specify either a metric to retrieve, a Metrics Insights query, or a math
     * expression to perform on retrieved data.
     *
     * @required
     *
     * @var MetricDataQuery[]|null
     */
    private $metricDataQueries;

    /**
     * The time stamp indicating the earliest data to be returned.
     *
     * The value specified is inclusive; results include data points with the specified time stamp.
     *
     * CloudWatch rounds the specified time stamp as follows:
     *
     * - Start time less than 15 days ago - Round down to the nearest whole minute. For example, 12:32:34 is rounded down to
     *   12:32:00.
     * - Start time between 15 and 63 days ago - Round down to the nearest 5-minute clock interval. For example, 12:32:34 is
     *   rounded down to 12:30:00.
     * - Start time greater than 63 days ago - Round down to the nearest 1-hour clock interval. For example, 12:32:34 is
     *   rounded down to 12:00:00.
     *
     * If you set `Period` to 5, 10, 20, or 30, the start time of your request is rounded down to the nearest time that
     * corresponds to even 5-, 10-, 20-, or 30-second divisions of a minute. For example, if you make a query at (HH:mm:ss)
     * 01:05:23 for the previous 10-second period, the start time of your request is rounded down and you receive data from
     * 01:05:10 to 01:05:20. If you make a query at 15:07:17 for the previous 5 minutes of data, using a period of 5
     * seconds, you receive data timestamped between 15:02:15 and 15:07:15.
     *
     * For better performance, specify `StartTime` and `EndTime` values that align with the value of the metric's `Period`
     * and sync up with the beginning and end of an hour. For example, if the `Period` of a metric is 5 minutes, specifying
     * 12:05 or 12:30 as `StartTime` can get a faster response from CloudWatch than setting 12:07 or 12:29 as the
     * `StartTime`.
     *
     * @required
     *
     * @var \DateTimeImmutable|null
     */
    private $startTime;

    /**
     * The time stamp indicating the latest data to be returned.
     *
     * The value specified is exclusive; results include data points up to the specified time stamp.
     *
     * For better performance, specify `StartTime` and `EndTime` values that align with the value of the metric's `Period`
     * and sync up with the beginning and end of an hour. For example, if the `Period` of a metric is 5 minutes, specifying
     * 12:05 or 12:30 as `EndTime` can get a faster response from CloudWatch than setting 12:07 or 12:29 as the `EndTime`.
     *
     * @required
     *
     * @var \DateTimeImmutable|null
     */
    private $endTime;

    /**
     * Include this value, if it was returned by the previous `GetMetricData` operation, to get the next set of data points.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * The order in which data points should be returned. `TimestampDescending` returns the newest data first and paginates
     * when the `MaxDatapoints` limit is reached. `TimestampAscending` returns the oldest data first and paginates when the
     * `MaxDatapoints` limit is reached.
     *
     * If you omit this parameter, the default of `TimestampDescending` is used.
     *
     * @var ScanBy::*|null
     */
    private $scanBy;

    /**
     * The maximum number of data points the request should return before paginating. If you omit this, the default of
     * 100,800 is used.
     *
     * @var int|null
     */
    private $maxDatapoints;

    /**
     * This structure includes the `Timezone` parameter, which you can use to specify your time zone so that the labels of
     * returned data display the correct time for your time zone.
     *
     * @var LabelOptions|null
     */
    private $labelOptions;

    /**
     * @param array{
     *   MetricDataQueries?: array<MetricDataQuery|array>,
     *   StartTime?: \DateTimeImmutable|string,
     *   EndTime?: \DateTimeImmutable|string,
     *   NextToken?: string|null,
     *   ScanBy?: ScanBy::*|null,
     *   MaxDatapoints?: int|null,
     *   LabelOptions?: LabelOptions|array|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->metricDataQueries = isset($input['MetricDataQueries']) ? array_map([MetricDataQuery::class, 'create'], $input['MetricDataQueries']) : null;
        $this->startTime = !isset($input['StartTime']) ? null : ($input['StartTime'] instanceof \DateTimeImmutable ? $input['StartTime'] : new \DateTimeImmutable($input['StartTime']));
        $this->endTime = !isset($input['EndTime']) ? null : ($input['EndTime'] instanceof \DateTimeImmutable ? $input['EndTime'] : new \DateTimeImmutable($input['EndTime']));
        $this->nextToken = $input['NextToken'] ?? null;
        $this->scanBy = $input['ScanBy'] ?? null;
        $this->maxDatapoints = $input['MaxDatapoints'] ?? null;
        $this->labelOptions = isset($input['LabelOptions']) ? LabelOptions::create($input['LabelOptions']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   MetricDataQueries?: array<MetricDataQuery|array>,
     *   StartTime?: \DateTimeImmutable|string,
     *   EndTime?: \DateTimeImmutable|string,
     *   NextToken?: string|null,
     *   ScanBy?: ScanBy::*|null,
     *   MaxDatapoints?: int|null,
     *   LabelOptions?: LabelOptions|array|null,
     *   '@region'?: string|null,
     * }|GetMetricDataInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function getLabelOptions(): ?LabelOptions
    {
        return $this->labelOptions;
    }

    public function getMaxDatapoints(): ?int
    {
        return $this->maxDatapoints;
    }

    /**
     * @return MetricDataQuery[]
     */
    public function getMetricDataQueries(): array
    {
        return $this->metricDataQueries ?? [];
    }

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return ScanBy::*|null
     */
    public function getScanBy(): ?string
    {
        return $this->scanBy;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'GraniteServiceVersion20100801.GetMetricData',
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

    public function setEndTime(?\DateTimeImmutable $value): self
    {
        $this->endTime = $value;

        return $this;
    }

    public function setLabelOptions(?LabelOptions $value): self
    {
        $this->labelOptions = $value;

        return $this;
    }

    public function setMaxDatapoints(?int $value): self
    {
        $this->maxDatapoints = $value;

        return $this;
    }

    /**
     * @param MetricDataQuery[] $value
     */
    public function setMetricDataQueries(array $value): self
    {
        $this->metricDataQueries = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param ScanBy::*|null $value
     */
    public function setScanBy(?string $value): self
    {
        $this->scanBy = $value;

        return $this;
    }

    public function setStartTime(?\DateTimeImmutable $value): self
    {
        $this->startTime = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->metricDataQueries) {
            throw new InvalidArgument(\sprintf('Missing parameter "MetricDataQueries" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['MetricDataQueries'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['MetricDataQueries'][$index] = $listValue->requestBody();
        }

        if (null === $v = $this->startTime) {
            throw new InvalidArgument(\sprintf('Missing parameter "StartTime" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StartTime'] = $v->getTimestamp();
        if (null === $v = $this->endTime) {
            throw new InvalidArgument(\sprintf('Missing parameter "EndTime" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['EndTime'] = $v->getTimestamp();
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->scanBy) {
            if (!ScanBy::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ScanBy" for "%s". The value "%s" is not a valid "ScanBy".', __CLASS__, $v));
            }
            $payload['ScanBy'] = $v;
        }
        if (null !== $v = $this->maxDatapoints) {
            $payload['MaxDatapoints'] = $v;
        }
        if (null !== $v = $this->labelOptions) {
            $payload['LabelOptions'] = $v->requestBody();
        }

        return $payload;
    }
}
