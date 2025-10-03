<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Datum represents a single data point in a query result.
 */
final class Datum
{
    /**
     * Indicates if the data point is a scalar value such as integer, string, double, or Boolean.
     *
     * @var string|null
     */
    private $scalarValue;

    /**
     * Indicates if the data point is a timeseries data type.
     *
     * @var TimeSeriesDataPoint[]|null
     */
    private $timeSeriesValue;

    /**
     * Indicates if the data point is an array.
     *
     * @var Datum[]|null
     */
    private $arrayValue;

    /**
     * Indicates if the data point is a row.
     *
     * @var Row|null
     */
    private $rowValue;

    /**
     * Indicates if the data point is null.
     *
     * @var bool|null
     */
    private $nullValue;

    /**
     * @param array{
     *   ScalarValue?: string|null,
     *   TimeSeriesValue?: array<TimeSeriesDataPoint|array>|null,
     *   ArrayValue?: array<Datum|array>|null,
     *   RowValue?: Row|array|null,
     *   NullValue?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->scalarValue = $input['ScalarValue'] ?? null;
        $this->timeSeriesValue = isset($input['TimeSeriesValue']) ? array_map([TimeSeriesDataPoint::class, 'create'], $input['TimeSeriesValue']) : null;
        $this->arrayValue = isset($input['ArrayValue']) ? array_map([Datum::class, 'create'], $input['ArrayValue']) : null;
        $this->rowValue = isset($input['RowValue']) ? Row::create($input['RowValue']) : null;
        $this->nullValue = $input['NullValue'] ?? null;
    }

    /**
     * @param array{
     *   ScalarValue?: string|null,
     *   TimeSeriesValue?: array<TimeSeriesDataPoint|array>|null,
     *   ArrayValue?: array<Datum|array>|null,
     *   RowValue?: Row|array|null,
     *   NullValue?: bool|null,
     * }|Datum $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Datum[]
     */
    public function getArrayValue(): array
    {
        return $this->arrayValue ?? [];
    }

    public function getNullValue(): ?bool
    {
        return $this->nullValue;
    }

    public function getRowValue(): ?Row
    {
        return $this->rowValue;
    }

    public function getScalarValue(): ?string
    {
        return $this->scalarValue;
    }

    /**
     * @return TimeSeriesDataPoint[]
     */
    public function getTimeSeriesValue(): array
    {
        return $this->timeSeriesValue ?? [];
    }
}
