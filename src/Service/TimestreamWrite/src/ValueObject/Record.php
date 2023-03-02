<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\TimestreamWrite\Enum\MeasureValueType;
use AsyncAws\TimestreamWrite\Enum\TimeUnit;

/**
 * A record that contains the common measure, dimension, time, and version attributes shared across all the records in
 * the request. The measure and dimension attributes specified will be merged with the measure and dimension attributes
 * in the records object when the data is written into Timestream. Dimensions may not overlap, or a
 * `ValidationException` will be thrown. In other words, a record must contain dimensions with unique names.
 */
final class Record
{
    /**
     * Contains the list of dimensions for time-series data points.
     */
    private $dimensions;

    /**
     * Measure represents the data attribute of the time series. For example, the CPU utilization of an EC2 instance or the
     * RPM of a wind turbine are measures.
     */
    private $measureName;

    /**
     * Contains the measure value for the time-series data point.
     */
    private $measureValue;

    /**
     * Contains the data type of the measure value for the time-series data point. Default type is `DOUBLE`.
     */
    private $measureValueType;

    /**
     * Contains the time at which the measure value for the data point was collected. The time value plus the unit provides
     * the time elapsed since the epoch. For example, if the time value is `12345` and the unit is `ms`, then `12345 ms`
     * have elapsed since the epoch.
     */
    private $time;

    /**
     * The granularity of the timestamp unit. It indicates if the time value is in seconds, milliseconds, nanoseconds, or
     * other supported values. Default is `MILLISECONDS`.
     */
    private $timeUnit;

    /**
     * 64-bit attribute used for record updates. Write requests for duplicate data with a higher version number will update
     * the existing measure value and version. In cases where the measure value is the same, `Version` will still be
     * updated. Default value is `1`.
     */
    private $version;

    /**
     * Contains the list of MeasureValue for time-series data points.
     */
    private $measureValues;

    /**
     * @param array{
     *   Dimensions?: null|Dimension[],
     *   MeasureName?: null|string,
     *   MeasureValue?: null|string,
     *   MeasureValueType?: null|MeasureValueType::*,
     *   Time?: null|string,
     *   TimeUnit?: null|TimeUnit::*,
     *   Version?: null|string,
     *   MeasureValues?: null|MeasureValue[],
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

    public function getVersion(): ?string
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
                throw new InvalidArgument(sprintf('Invalid parameter "MeasureValueType" for "%s". The value "%s" is not a valid "MeasureValueType".', __CLASS__, $v));
            }
            $payload['MeasureValueType'] = $v;
        }
        if (null !== $v = $this->time) {
            $payload['Time'] = $v;
        }
        if (null !== $v = $this->timeUnit) {
            if (!TimeUnit::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "TimeUnit" for "%s". The value "%s" is not a valid "TimeUnit".', __CLASS__, $v));
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
