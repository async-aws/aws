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
     *
     * @var Row[]|null
     */
    private $rows;

    /**
     * The metadata that describes the column structure and data types of a table of query results.
     *
     * @var ResultSetMetadata|null
     */
    private $resultSetMetadata;

    /**
     * @param array{
     *   Rows?: array<Row|array>|null,
     *   ResultSetMetadata?: ResultSetMetadata|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->rows = isset($input['Rows']) ? array_map([Row::class, 'create'], $input['Rows']) : null;
        $this->resultSetMetadata = isset($input['ResultSetMetadata']) ? ResultSetMetadata::create($input['ResultSetMetadata']) : null;
    }

    /**
     * @param array{
     *   Rows?: array<Row|array>|null,
     *   ResultSetMetadata?: ResultSetMetadata|array|null,
     * }|ResultSet $input
     */
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
