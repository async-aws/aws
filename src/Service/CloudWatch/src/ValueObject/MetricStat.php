<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\CloudWatch\Enum\StandardUnit;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The metric to be returned, along with statistics, period, and units. Use this parameter only if this object is
 * retrieving a metric and not performing a math expression on returned data.
 * Within one MetricDataQuery object, you must specify either `Expression` or `MetricStat` but not both.
 */
final class MetricStat
{
    /**
     * The metric to return, including the metric name, namespace, and dimensions.
     */
    private $metric;

    /**
     * The granularity, in seconds, of the returned data points. For metrics with regular resolution, a period can be as
     * short as one minute (60 seconds) and must be a multiple of 60. For high-resolution metrics that are collected at
     * intervals of less than one minute, the period can be 1, 5, 10, 30, 60, or any multiple of 60. High-resolution metrics
     * are those metrics stored by a `PutMetricData` call that includes a `StorageResolution` of 1 second.
     */
    private $period;

    /**
     * The statistic to return. It can include any CloudWatch statistic or extended statistic.
     */
    private $stat;

    /**
     * When you are using a `Put` operation, this defines what unit you want to use when storing the metric.
     */
    private $unit;

    /**
     * @param array{
     *   Metric: Metric|array,
     *   Period: int,
     *   Stat: string,
     *   Unit?: null|StandardUnit::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->metric = isset($input['Metric']) ? Metric::create($input['Metric']) : null;
        $this->period = $input['Period'] ?? null;
        $this->stat = $input['Stat'] ?? null;
        $this->unit = $input['Unit'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMetric(): Metric
    {
        return $this->metric;
    }

    public function getPeriod(): int
    {
        return $this->period;
    }

    public function getStat(): string
    {
        return $this->stat;
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
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->metric) {
            throw new InvalidArgument(sprintf('Missing parameter "Metric" for "%s". The value cannot be null.', __CLASS__));
        }
        foreach ($v->requestBody() as $bodyKey => $bodyValue) {
            $payload["Metric.$bodyKey"] = $bodyValue;
        }

        if (null === $v = $this->period) {
            throw new InvalidArgument(sprintf('Missing parameter "Period" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Period'] = $v;
        if (null === $v = $this->stat) {
            throw new InvalidArgument(sprintf('Missing parameter "Stat" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Stat'] = $v;
        if (null !== $v = $this->unit) {
            if (!StandardUnit::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Unit" for "%s". The value "%s" is not a valid "StandardUnit".', __CLASS__, $v));
            }
            $payload['Unit'] = $v;
        }

        return $payload;
    }
}
