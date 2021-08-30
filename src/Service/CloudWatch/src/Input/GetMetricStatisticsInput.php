<?php

namespace AsyncAws\CloudWatch\Input;

use AsyncAws\CloudWatch\Enum\StandardUnit;
use AsyncAws\CloudWatch\Enum\Statistic;
use AsyncAws\CloudWatch\ValueObject\Dimension;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class GetMetricStatisticsInput extends Input
{
    /**
     * The namespace of the metric, with or without spaces.
     *
     * @required
     *
     * @var string|null
     */
    private $namespace;

    /**
     * The name of the metric, with or without spaces.
     *
     * @required
     *
     * @var string|null
     */
    private $metricName;

    /**
     * The dimensions. If the metric contains multiple dimensions, you must include a value for each dimension. CloudWatch
     * treats each unique combination of dimensions as a separate metric. If a specific combination of dimensions was not
     * published, you can't retrieve statistics for it. You must specify the same dimensions that were used when the metrics
     * were created. For an example, see Dimension Combinations in the *Amazon CloudWatch User Guide*. For more information
     * about specifying dimensions, see Publishing Metrics in the *Amazon CloudWatch User Guide*.
     *
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/cloudwatch_concepts.html#dimension-combinations
     * @see https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/publishingMetrics.html
     *
     * @var Dimension[]|null
     */
    private $dimensions;

    /**
     * The time stamp that determines the first data point to return. Start times are evaluated relative to the time that
     * CloudWatch receives the request.
     *
     * @required
     *
     * @var \DateTimeImmutable|null
     */
    private $startTime;

    /**
     * The time stamp that determines the last data point to return.
     *
     * @required
     *
     * @var \DateTimeImmutable|null
     */
    private $endTime;

    /**
     * The granularity, in seconds, of the returned data points. For metrics with regular resolution, a period can be as
     * short as one minute (60 seconds) and must be a multiple of 60. For high-resolution metrics that are collected at
     * intervals of less than one minute, the period can be 1, 5, 10, 30, 60, or any multiple of 60. High-resolution metrics
     * are those metrics stored by a `PutMetricData` call that includes a `StorageResolution` of 1 second.
     *
     * @required
     *
     * @var int|null
     */
    private $period;

    /**
     * The metric statistics, other than percentile. For percentile statistics, use `ExtendedStatistics`. When calling
     * `GetMetricStatistics`, you must specify either `Statistics` or `ExtendedStatistics`, but not both.
     *
     * @var null|list<Statistic::*>
     */
    private $statistics;

    /**
     * The percentile statistics. Specify values between p0.0 and p100. When calling `GetMetricStatistics`, you must specify
     * either `Statistics` or `ExtendedStatistics`, but not both. Percentile statistics are not available for metrics when
     * any of the metric values are negative numbers.
     *
     * @var string[]|null
     */
    private $extendedStatistics;

    /**
     * The unit for a given metric. If you omit `Unit`, all data that was collected with any unit is returned, along with
     * the corresponding units that were specified when the data was reported to CloudWatch. If you specify a unit, the
     * operation returns only data that was collected with that unit specified. If you specify a unit that does not match
     * the data collected, the results of the operation are null. CloudWatch does not perform unit conversions.
     *
     * @var null|StandardUnit::*
     */
    private $unit;

    /**
     * @param array{
     *   Namespace?: string,
     *   MetricName?: string,
     *   Dimensions?: Dimension[],
     *   StartTime?: \DateTimeImmutable|string,
     *   EndTime?: \DateTimeImmutable|string,
     *   Period?: int,
     *   Statistics?: list<Statistic::*>,
     *   ExtendedStatistics?: string[],
     *   Unit?: StandardUnit::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->namespace = $input['Namespace'] ?? null;
        $this->metricName = $input['MetricName'] ?? null;
        $this->dimensions = isset($input['Dimensions']) ? array_map([Dimension::class, 'create'], $input['Dimensions']) : null;
        $this->startTime = !isset($input['StartTime']) ? null : ($input['StartTime'] instanceof \DateTimeImmutable ? $input['StartTime'] : new \DateTimeImmutable($input['StartTime']));
        $this->endTime = !isset($input['EndTime']) ? null : ($input['EndTime'] instanceof \DateTimeImmutable ? $input['EndTime'] : new \DateTimeImmutable($input['EndTime']));
        $this->period = $input['Period'] ?? null;
        $this->statistics = $input['Statistics'] ?? null;
        $this->extendedStatistics = $input['ExtendedStatistics'] ?? null;
        $this->unit = $input['Unit'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Dimension[]
     */
    public function getDimensions(): array
    {
        return $this->dimensions ?? [];
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    /**
     * @return string[]
     */
    public function getExtendedStatistics(): array
    {
        return $this->extendedStatistics ?? [];
    }

    public function getMetricName(): ?string
    {
        return $this->metricName;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    /**
     * @return list<Statistic::*>
     */
    public function getStatistics(): array
    {
        return $this->statistics ?? [];
    }

    /**
     * @return StandardUnit::*|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
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
        $body = http_build_query(['Action' => 'GetMetricStatistics', 'Version' => '2010-08-01'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param Dimension[] $value
     */
    public function setDimensions(array $value): self
    {
        $this->dimensions = $value;

        return $this;
    }

    public function setEndTime(?\DateTimeImmutable $value): self
    {
        $this->endTime = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setExtendedStatistics(array $value): self
    {
        $this->extendedStatistics = $value;

        return $this;
    }

    public function setMetricName(?string $value): self
    {
        $this->metricName = $value;

        return $this;
    }

    public function setNamespace(?string $value): self
    {
        $this->namespace = $value;

        return $this;
    }

    public function setPeriod(?int $value): self
    {
        $this->period = $value;

        return $this;
    }

    public function setStartTime(?\DateTimeImmutable $value): self
    {
        $this->startTime = $value;

        return $this;
    }

    /**
     * @param list<Statistic::*> $value
     */
    public function setStatistics(array $value): self
    {
        $this->statistics = $value;

        return $this;
    }

    /**
     * @param StandardUnit::*|null $value
     */
    public function setUnit(?string $value): self
    {
        $this->unit = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->namespace) {
            throw new InvalidArgument(sprintf('Missing parameter "Namespace" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Namespace'] = $v;
        if (null === $v = $this->metricName) {
            throw new InvalidArgument(sprintf('Missing parameter "MetricName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['MetricName'] = $v;
        if (null !== $v = $this->dimensions) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["Dimensions.member.$index.$bodyKey"] = $bodyValue;
                }
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
        if (null === $v = $this->period) {
            throw new InvalidArgument(sprintf('Missing parameter "Period" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Period'] = $v;
        if (null !== $v = $this->statistics) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                if (!Statistic::exists($mapValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "Statistics.member" for "%s". The value "%s" is not a valid "Statistic".', __CLASS__, $mapValue));
                }
                $payload["Statistics.member.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->extendedStatistics) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["ExtendedStatistics.member.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->unit) {
            if (!StandardUnit::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Unit" for "%s". The value "%s" is not a valid "StandardUnit".', __CLASS__, $v));
            }
            $payload['Unit'] = $v;
        }

        return $payload;
    }
}
