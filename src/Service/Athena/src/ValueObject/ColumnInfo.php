<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\ColumnNullable;

/**
 * Information about the columns in a query execution result.
 */
final class ColumnInfo
{
    /**
     * The catalog to which the query results belong.
     */
    private $catalogName;

    /**
     * The schema name (database name) to which the query results belong.
     */
    private $schemaName;

    /**
     * The table name for the query results.
     */
    private $tableName;

    /**
     * The name of the column.
     */
    private $name;

    /**
     * A column label.
     */
    private $label;

    /**
     * The data type of the column.
     */
    private $type;

    /**
     * For `DECIMAL` data types, specifies the total number of digits, up to 38. For performance reasons, we recommend up to
     * 18 digits.
     */
    private $precision;

    /**
     * For `DECIMAL` data types, specifies the total number of digits in the fractional part of the value. Defaults to 0.
     */
    private $scale;

    /**
     * Indicates the column's nullable status.
     */
    private $nullable;

    /**
     * Indicates whether values in the column are case-sensitive.
     */
    private $caseSensitive;

    /**
     * @param array{
     *   CatalogName?: null|string,
     *   SchemaName?: null|string,
     *   TableName?: null|string,
     *   Name: string,
     *   Label?: null|string,
     *   Type: string,
     *   Precision?: null|int,
     *   Scale?: null|int,
     *   Nullable?: null|ColumnNullable::*,
     *   CaseSensitive?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->catalogName = $input['CatalogName'] ?? null;
        $this->schemaName = $input['SchemaName'] ?? null;
        $this->tableName = $input['TableName'] ?? null;
        $this->name = $input['Name'] ?? null;
        $this->label = $input['Label'] ?? null;
        $this->type = $input['Type'] ?? null;
        $this->precision = $input['Precision'] ?? null;
        $this->scale = $input['Scale'] ?? null;
        $this->nullable = $input['Nullable'] ?? null;
        $this->caseSensitive = $input['CaseSensitive'] ?? null;
    }

    /**
     * @param array{
     *   CatalogName?: null|string,
     *   SchemaName?: null|string,
     *   TableName?: null|string,
     *   Name: string,
     *   Label?: null|string,
     *   Type: string,
     *   Precision?: null|int,
     *   Scale?: null|int,
     *   Nullable?: null|ColumnNullable::*,
     *   CaseSensitive?: null|bool,
     * }|ColumnInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCaseSensitive(): ?bool
    {
        return $this->caseSensitive;
    }

    public function getCatalogName(): ?string
    {
        return $this->catalogName;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ColumnNullable::*|null
     */
    public function getNullable(): ?string
    {
        return $this->nullable;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function getSchemaName(): ?string
    {
        return $this->schemaName;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
