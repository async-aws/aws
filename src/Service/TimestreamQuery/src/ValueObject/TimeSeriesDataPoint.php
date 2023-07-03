<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The timeseries data type represents the values of a measure over time. A time series is an array of rows of
 * timestamps and measure values, with rows sorted in ascending order of time. A TimeSeriesDataPoint is a single data
 * point in the time series. It represents a tuple of (time, measure value) in a time series.
 */
final class TimeSeriesDataPoint
{
    /**
     * The timestamp when the measure value was collected.
     *
     * @var string
     */
    private $time;

    /**
     * The measure value for the data point.
     *
     * @var Datum
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
        $this->time = $input['Time'] ?? $this->throwException(new InvalidArgument('Missing required field "Time".'));
        $this->value = isset($input['Value']) ? Datum::create($input['Value']) : $this->throwException(new InvalidArgument('Missing required field "Value".'));
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
