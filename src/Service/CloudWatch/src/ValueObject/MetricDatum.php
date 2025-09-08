<?php

namespace AsyncAws\CloudWatch\ValueObject;

use AsyncAws\CloudWatch\Enum\StandardUnit;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Encapsulates the information sent to either create a metric or add new values to be aggregated into an existing
 * metric.
 */
final class MetricDatum
{
    /**
     * The name of the metric.
     *
     * @var string
     */
    private $metricName;

    /**
     * The dimensions associated with the metric.
     *
     * @var Dimension[]|null
     */
    private $dimensions;

    /**
     * The time the metric data was received, expressed as the number of milliseconds since Jan 1, 1970 00:00:00 UTC.
     *
     * @var \DateTimeImmutable|null
     */
    private $timestamp;

    /**
     * The value for the metric.
     *
     * Although the parameter accepts numbers of type Double, CloudWatch rejects values that are either too small or too
     * large. Values must be in the range of -2^360 to 2^360. In addition, special values (for example, NaN, +Infinity,
     * -Infinity) are not supported.
     *
     * @var float|null
     */
    private $value;

    /**
     * The statistical values for the metric.
     *
     * @var StatisticSet|null
     */
    private $statisticValues;

    /**
     * Array of numbers representing the values for the metric during the period. Each unique value is listed just once in
     * this array, and the corresponding number in the `Counts` array specifies the number of times that value occurred
     * during the period. You can include up to 150 unique values in each `PutMetricData` action that specifies a `Values`
     * array.
     *
     * Although the `Values` array accepts numbers of type `Double`, CloudWatch rejects values that are either too small or
     * too large. Values must be in the range of -2^360 to 2^360. In addition, special values (for example, NaN, +Infinity,
     * -Infinity) are not supported.
     *
     * @var float[]|null
     */
    private $values;

    /**
     * Array of numbers that is used along with the `Values` array. Each number in the `Count` array is the number of times
     * the corresponding value in the `Values` array occurred during the period.
     *
     * If you omit the `Counts` array, the default of 1 is used as the value for each count. If you include a `Counts`
     * array, it must include the same amount of values as the `Values` array.
     *
     * @var float[]|null
     */
    private $counts;

    /**
     * When you are using a `Put` operation, this defines what unit you want to use when storing the metric.
     *
     * In a `Get` operation, this displays the unit that is used for the metric.
     *
     * @var StandardUnit::*|null
     */
    private $unit;

    /**
     * Valid values are 1 and 60. Setting this to 1 specifies this metric as a high-resolution metric, so that CloudWatch
     * stores the metric with sub-minute resolution down to one second. Setting this to 60 specifies this metric as a
     * regular-resolution metric, which CloudWatch stores at 1-minute resolution. Currently, high resolution is available
     * only for custom metrics. For more information about high-resolution metrics, see High-Resolution Metrics [^1] in the
     * *Amazon CloudWatch User Guide*.
     *
     * This field is optional, if you do not specify it the default of 60 is used.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/publishingMetrics.html#high-resolution-metrics
     *
     * @var int|null
     */
    private $storageResolution;

    /**
     * @param array{
     *   MetricName: string,
     *   Dimensions?: array<Dimension|array>|null,
     *   Timestamp?: \DateTimeImmutable|null,
     *   Value?: float|null,
     *   StatisticValues?: StatisticSet|array|null,
     *   Values?: float[]|null,
     *   Counts?: float[]|null,
     *   Unit?: StandardUnit::*|null,
     *   StorageResolution?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->metricName = $input['MetricName'] ?? $this->throwException(new InvalidArgument('Missing required field "MetricName".'));
        $this->dimensions = isset($input['Dimensions']) ? array_map([Dimension::class, 'create'], $input['Dimensions']) : null;
        $this->timestamp = $input['Timestamp'] ?? null;
        $this->value = $input['Value'] ?? null;
        $this->statisticValues = isset($input['StatisticValues']) ? StatisticSet::create($input['StatisticValues']) : null;
        $this->values = $input['Values'] ?? null;
        $this->counts = $input['Counts'] ?? null;
        $this->unit = $input['Unit'] ?? null;
        $this->storageResolution = $input['StorageResolution'] ?? null;
    }

    /**
     * @param array{
     *   MetricName: string,
     *   Dimensions?: array<Dimension|array>|null,
     *   Timestamp?: \DateTimeImmutable|null,
     *   Value?: float|null,
     *   StatisticValues?: StatisticSet|array|null,
     *   Values?: float[]|null,
     *   Counts?: float[]|null,
     *   Unit?: StandardUnit::*|null,
     *   StorageResolution?: int|null,
     * }|MetricDatum $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return float[]
     */
    public function getCounts(): array
    {
        return $this->counts ?? [];
    }

    /**
     * @return Dimension[]
     */
    public function getDimensions(): array
    {
        return $this->dimensions ?? [];
    }

    public function getMetricName(): string
    {
        return $this->metricName;
    }

    public function getStatisticValues(): ?StatisticSet
    {
        return $this->statisticValues;
    }

    public function getStorageResolution(): ?int
    {
        return $this->storageResolution;
    }

    public function getTimestamp(): ?\DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @return StandardUnit::*|null
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    /**
     * @return float[]
     */
    public function getValues(): array
    {
        return $this->values ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->metricName;
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
        if (null !== $v = $this->timestamp) {
            $payload['Timestamp'] = $v->format(\DateTimeInterface::ATOM);
        }
        if (null !== $v = $this->value) {
            $payload['Value'] = $v;
        }
        if (null !== $v = $this->statisticValues) {
            foreach ($v->requestBody() as $bodyKey => $bodyValue) {
                $payload["StatisticValues.$bodyKey"] = $bodyValue;
            }
        }
        if (null !== $v = $this->values) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["Values.member.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->counts) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["Counts.member.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->unit) {
            if (!StandardUnit::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "Unit" for "%s". The value "%s" is not a valid "StandardUnit".', __CLASS__, $v));
            }
            $payload['Unit'] = $v;
        }
        if (null !== $v = $this->storageResolution) {
            $payload['StorageResolution'] = $v;
        }

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
