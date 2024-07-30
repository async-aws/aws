<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;
use AsyncAws\TimestreamWrite\Enum\TimeUnit;

/**
 * Represents a time-series data point being written into Timestream. Each record contains an array of dimensions.
 * Dimensions represent the metadata attributes of a time-series data point, such as the instance name or Availability
 * Zone of an EC2 instance. A record also contains the measure name, which is the name of the measure being collected
 * (for example, the CPU utilization of an EC2 instance). Additionally, a record contains the measure value and the
 * value type, which is the data type of the measure value. Also, the record contains the timestamp of when the measure
 * was collected and the timestamp unit, which represents the granularity of the timestamp.
 *
 * Records have a `Version` field, which is a 64-bit `long` that you can use for updating data points. Writes of a
 * duplicate record with the same dimension, timestamp, and measure name but different measure value will only succeed
 * if the `Version` attribute of the record in the write request is higher than that of the existing record. Timestream
 * defaults to a `Version` of `1` for records without the `Version` field.
 */
final class Record
{
    /**
     * Contains the list of dimensions for time-series data points.
     *
     * @var Dimension[]|null
     */
    private $dimensions;

    /**
     * Measure represents the data attribute of the time series. For example, the CPU utilization of an EC2 instance or the
     * RPM of a wind turbine are measures.
     *
     * @var string|null
     */
    private $measureName;

    /**
     * Contains the measure value for the time-series data point.
     *
     * @var string|null
     */
    private $measureValue;

    /**
     * Contains the data type of the measure value for the time-series data point. Default type is `DOUBLE`. For more
     * information, see Data types [^1].
     *
     * [^1]: https://docs.aws.amazon.com/timestream/latest/developerguide/writes.html#writes.data-types
     *
     * @var MeasureValueType::*|null
     */
    private $measureValueType;

    /**
     * Contains the time at which the measure value for the data point was collected. The time value plus the unit provides
     * the time elapsed since the epoch. For example, if the time value is `12345` and the unit is `ms`, then `12345 ms`
     * have elapsed since the epoch.
     *
     * @var string|null
     */
    private $time;

    /**
     * The granularity of the timestamp unit. It indicates if the time value is in seconds, milliseconds, nanoseconds, or
     * other supported values. Default is `MILLISECONDS`.
     *
     * @var TimeUnit::*|null
     */
    private $timeUnit;

    /**
     * 64-bit attribute used for record updates. Write requests for duplicate data with a higher version number will update
     * the existing measure value and version. In cases where the measure value is the same, `Version` will still be
     * updated. Default value is `1`.
     *
     * > `Version` must be `1` or greater, or you will receive a `ValidationException` error.
     *
     * @var int|null
     */
    private $version;

    /**
     * Contains the list of MeasureValue for time-series data points.
     *
     * This is only allowed for type `MULTI`. For scalar values, use `MeasureValue` attribute of the record directly.
     *
     * @var MeasureValue[]|null
     */
    private $measureValues;

    /**
     * @param array{
     *   Dimensions?: null|array<Dimension|array>,
     *   MeasureName?: null|string,
     *   MeasureValue?: null|string,
     *   MeasureValueType?: null|MeasureValueType::*,
     *   Time?: null|string,
     *   TimeUnit?: null|TimeUnit::*,
     *   Version?: null|int,
     *   MeasureValues?: null|array<MeasureValue|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dimensions = isset($input['Dimensions']) ? array_map([Dimension::class, 'create'], $input['Dimensions']) : null;
        $this->measureName = $input['MeasureName'] ?? null;
        $this->measureValue = $input['MeasureValue'] ?? null;
        $this->measureValueType = $input['MeasureValueType'] ?? null;
        $this->time = $input['Time'] ?? null;
        $this->timeUnit = $input['TimeUnit'] ?? null;
        $this->version = $input['Version'] ?? null;
        $this->measureValues = isset($input['MeasureValues']) ? array_map([MeasureValue::class, 'create'], $input['MeasureValues']) : null;
    }

    /**
     * @param array{
     *   Dimensions?: null|array<Dimension|array>,
     *   MeasureName?: null|string,
     *   MeasureValue?: null|string,
     *   MeasureValueType?: null|MeasureValueType::*,
     *   Time?: null|string,
     *   TimeUnit?: null|TimeUnit::*,
     *   Version?: null|int,
     *   MeasureValues?: null|array<MeasureValue|array>,
     * }|Record $input
     */
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

    public function getMeasureName(): ?string
    {
        return $this->measureName;
    }

    public function getMeasureValue(): ?string
    {
        return $this->measureValue;
    }

    /**
     * @return MeasureValueType::*|null
     */
    public function getMeasureValueType(): ?string
    {
        return $this->measureValueType;
    }

    /**
     * @return MeasureValue[]
     */
    public function getMeasureValues(): array
    {
        return $this->measureValues ?? [];
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    /**
     * @return TimeUnit::*|null
     */
    public function getTimeUnit(): ?string
    {
        return $this->timeUnit;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->dimensions) {
            $index = -1;
            $payload['Dimensions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Dimensions'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->measureName) {
            $payload['MeasureName'] = $v;
        }
        if (null !== $v = $this->measureValue) {
            $payload['MeasureValue'] = $v;
        }
        if (null !== $v = $this->measureValueType) {
            if (!MeasureValueType::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "MeasureValueType" for "%s". The value "%s" is not a valid "MeasureValueType".', __CLASS__, $v));
            }
            $payload['MeasureValueType'] = $v;
        }
        if (null !== $v = $this->time) {
            $payload['Time'] = $v;
        }
        if (null !== $v = $this->timeUnit) {
            if (!TimeUnit::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "TimeUnit" for "%s". The value "%s" is not a valid "TimeUnit".', __CLASS__, $v));
            }
            $payload['TimeUnit'] = $v;
        }
        if (null !== $v = $this->version) {
            $payload['Version'] = $v;
        }
        if (null !== $v = $this->measureValues) {
            $index = -1;
            $payload['MeasureValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['MeasureValues'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
