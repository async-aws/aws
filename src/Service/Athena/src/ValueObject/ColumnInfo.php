<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Athena\Enum\ColumnNullable;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Information about the columns in a query execution result.
 */
final class ColumnInfo
{
    /**
     * The catalog to which the query results belong.
     *
     * @var string|null
     */
    private $catalogName;

    /**
     * The schema name (database name) to which the query results belong.
     *
     * @var string|null
     */
    private $schemaName;

    /**
     * The table name for the query results.
     *
     * @var string|null
     */
    private $tableName;

    /**
     * The name of the column.
     *
     * @var string
     */
    private $name;

    /**
     * A column label.
     *
     * @var string|null
     */
    private $label;

    /**
     * The data type of the column.
     *
     * @var string
     */
    private $type;

    /**
     * For `DECIMAL` data types, specifies the total number of digits, up to 38. For performance reasons, we recommend up to
     * 18 digits.
     *
     * @var int|null
     */
    private $precision;

    /**
     * For `DECIMAL` data types, specifies the total number of digits in the fractional part of the value. Defaults to 0.
     *
     * @var int|null
     */
    private $scale;

    /**
     * Unsupported constraint. This value always shows as `UNKNOWN`.
     *
     * @var ColumnNullable::*|null
     */
    private $nullable;

    /**
     * Indicates whether values in the column are case-sensitive.
     *
     * @var bool|null
     */
    private $caseSensitive;

    /**
     * @param array{
     *   CatalogName?: string|null,
     *   SchemaName?: string|null,
     *   TableName?: string|null,
     *   Name: string,
     *   Label?: string|null,
     *   Type: string,
     *   Precision?: int|null,
     *   Scale?: int|null,
     *   Nullable?: ColumnNullable::*|null,
     *   CaseSensitive?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->catalogName = $input['CatalogName'] ?? null;
        $this->schemaName = $input['SchemaName'] ?? null;
        $this->tableName = $input['TableName'] ?? null;
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->label = $input['Label'] ?? null;
        $this->type = $input['Type'] ?? $this->throwException(new InvalidArgument('Missing required field "Type".'));
        $this->precision = $input['Precision'] ?? null;
        $this->scale = $input['Scale'] ?? null;
        $this->nullable = $input['Nullable'] ?? null;
        $this->caseSensitive = $input['CaseSensitive'] ?? null;
    }

    /**
     * @param array{
     *   CatalogName?: string|null,
     *   SchemaName?: string|null,
     *   TableName?: string|null,
     *   Name: string,
     *   Label?: string|null,
     *   Type: string,
     *   Precision?: int|null,
     *   Scale?: int|null,
     *   Nullable?: ColumnNullable::*|null,
     *   CaseSensitive?: bool|null,
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
