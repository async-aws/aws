<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

use AsyncAws\TimestreamQuery\Enum\ScalarType;

/**
 * Contains the data type of a column in a query result set. The data type can be scalar or complex. The supported
 * scalar data types are integers, Boolean, string, double, timestamp, date, time, and intervals. The supported complex
 * data types are arrays, rows, and timeseries.
 */
final class Type
{
    /**
     * Indicates if the column is of type string, integer, Boolean, double, timestamp, date, time.
     */
    private $scalarType;

    /**
     * Indicates if the column is an array.
     */
    private $arrayColumnInfo;

    /**
     * Indicates if the column is a timeseries data type.
     */
    private $timeSeriesMeasureValueColumnInfo;

    /**
     * Indicates if the column is a row.
     */
    private $rowColumnInfo;

    /**
     * @param array{
     *   ScalarType?: null|ScalarType::*,
     *   ArrayColumnInfo?: null|ColumnInfo|array,
     *   TimeSeriesMeasureValueColumnInfo?: null|ColumnInfo|array,
     *   RowColumnInfo?: null|array<ColumnInfo|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->scalarType = $input['ScalarType'] ?? null;
        $this->arrayColumnInfo = isset($input['ArrayColumnInfo']) ? ColumnInfo::create($input['ArrayColumnInfo']) : null;
        $this->timeSeriesMeasureValueColumnInfo = isset($input['TimeSeriesMeasureValueColumnInfo']) ? ColumnInfo::create($input['TimeSeriesMeasureValueColumnInfo']) : null;
        $this->rowColumnInfo = isset($input['RowColumnInfo']) ? array_map([ColumnInfo::class, 'create'], $input['RowColumnInfo']) : null;
    }

    /**
     * @param array{
     *   ScalarType?: null|ScalarType::*,
     *   ArrayColumnInfo?: null|ColumnInfo|array,
     *   TimeSeriesMeasureValueColumnInfo?: null|ColumnInfo|array,
     *   RowColumnInfo?: null|array<ColumnInfo|array>,
     * }|Type $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArrayColumnInfo(): ?ColumnInfo
    {
        return $this->arrayColumnInfo;
    }

    /**
     * @return ColumnInfo[]
     */
    public function getRowColumnInfo(): array
    {
        return $this->rowColumnInfo ?? [];
    }

    /**
     * @return ScalarType::*|null
     */
    public function getScalarType(): ?string
    {
        return $this->scalarType;
    }

    public function getTimeSeriesMeasureValueColumnInfo(): ?ColumnInfo
    {
        return $this->timeSeriesMeasureValueColumnInfo;
    }
}
