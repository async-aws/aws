<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * The metadata and rows that make up a query result set. The metadata describes the column structure and data types. To
 * return a `ResultSet` object, use GetQueryResults.
 */
final class ResultSet
{
    /**
     * The rows in the table.
     */
    private $rows;

    /**
     * The metadata that describes the column structure and data types of a table of query results.
     */
    private $resultSetMetadata;

    /**
     * @param array{
     *   Rows?: null|Row[],
     *   ResultSetMetadata?: null|ResultSetMetadata|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rows = isset($input['Rows']) ? array_map([Row::class, 'create'], $input['Rows']) : null;
        $this->resultSetMetadata = isset($input['ResultSetMetadata']) ? ResultSetMetadata::create($input['ResultSetMetadata']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getResultSetMetadata(): ?ResultSetMetadata
    {
        return $this->resultSetMetadata;
    }

    /**
     * @return Row[]
     */
    public function getRows(): array
    {
        return $this->rows ?? [];
    }
}
