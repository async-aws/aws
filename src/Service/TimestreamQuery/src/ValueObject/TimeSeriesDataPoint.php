<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * The timeseries data type represents the values of a measure over time. A time series is an array of rows of
 * timestamps and measure values, with rows sorted in ascending order of time. A TimeSeriesDataPoint is a single data
 * point in the time series. It represents a tuple of (time, measure value) in a time series.
 */
final class TimeSeriesDataPoint
{
    /**
     * The timestamp when the measure value was collected.
     */
    private $time;

    /**
     * The measure value for the data point.
     */
    private $value;

    /**
     * @param array{
     *   Time: string,
     *   Value: Datum|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->time = $input['Time'] ?? null;
        $this->value = isset($input['Value']) ? Datum::create($input['Value']) : null;
    }

    /**
     * @param array{
     *   Time: string,
     *   Value: Datum|array,
     * }|TimeSeriesDataPoint $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function getValue(): Datum
    {
        return $this->value;
    }
}
