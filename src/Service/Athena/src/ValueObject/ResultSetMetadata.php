<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The metadata that describes the column structure and data types of a table of query results. To return a
 * `ResultSetMetadata` object, use GetQueryResults.
 */
final class ResultSetMetadata
{
    /**
     * Information about the columns returned in a query result metadata.
     */
    private $columnInfo;

    /**
     * @param array{
     *   ColumnInfo?: null|array<ColumnInfo|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->columnInfo = isset($input['ColumnInfo']) ? array_map([ColumnInfo::class, 'create'], $input['ColumnInfo']) : null;
    }

    /**
     * @param array{
     *   ColumnInfo?: null|array<ColumnInfo|array>,
     * }|ResultSetMetadata $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ColumnInfo[]
     */
    public function getColumnInfo(): array
    {
        return $this->columnInfo ?? [];
    }
}
