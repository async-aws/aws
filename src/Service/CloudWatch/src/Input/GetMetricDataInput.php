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
     * structures. Each of these structures can specify either a metric to retrieve, or a math expression to perform on
     * retrieved data.
     *
     * @required
     *
     * @var MetricDataQuery[]|null
     */
    private $metricDataQueries;

    /**
     * The time stamp indicating the earliest data to be returned.
     *
     * @required
     *
     * @var \DateTimeImmutable|null
     */
    private $startTime;

    /**
     * The time stamp indicating the latest data to be returned.
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
     *   MetricDataQueries?: MetricDataQuery[],
     *   StartTime?: \DateTimeImmutable|string,
     *   EndTime?: \DateTimeImmutable|string,
     *   NextToken?: string,
     *   ScanBy?: ScanBy::*,
     *   MaxDatapoints?: int,
     *   LabelOptions?: LabelOptions|array,
     *   @region?: string,
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
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'GetMetricData', 'Version' => '2010-08-01'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

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
            throw new InvalidArgument(sprintf('Missing parameter "MetricDataQueries" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = 0;
        foreach ($v as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["MetricDataQueries.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        if (null === $v = $this->startTime) {
            throw new InvalidArgument(sprintf('Missing parameter "StartTime" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['StartTime'] = $v->format(\DateTimeInterface::ATOM);
        if (null === $v = $this->endTime) {
            throw new InvalidArgument(sprintf('Missing parameter "EndTime" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['EndTime'] = $v->format(\DateTimeInterface::ATOM);
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->scanBy) {
            if (!ScanBy::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ScanBy" for "%s". The value "%s" is not a valid "ScanBy".', __CLASS__, $v));
            }
            $payload['ScanBy'] = $v;
        }
        if (null !== $v = $this->maxDatapoints) {
            $payload['MaxDatapoints'] = $v;
        }
        if (null !== $v = $this->labelOptions) {
            foreach ($v->requestBody() as $bodyKey => $bodyValue) {
                $payload["LabelOptions.$bodyKey"] = $bodyValue;
            }
        }

        return $payload;
    }
}
