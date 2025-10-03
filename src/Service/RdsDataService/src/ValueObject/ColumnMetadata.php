<?php

namespace AsyncAws\RdsDataService\ValueObject;

/**
 * Contains the metadata for a column.
 */
final class ColumnMetadata
{
    /**
     * The name of the column.
     *
     * @var string|null
     */
    private $name;

    /**
     * The type of the column.
     *
     * @var int|null
     */
    private $type;

    /**
     * The database-specific data type of the column.
     *
     * @var string|null
     */
    private $typeName;

    /**
     * The label for the column.
     *
     * @var string|null
     */
    private $label;

    /**
     * The name of the schema that owns the table that includes the column.
     *
     * @var string|null
     */
    private $schemaName;

    /**
     * The name of the table that includes the column.
     *
     * @var string|null
     */
    private $tableName;

    /**
     * A value that indicates whether the column increments automatically.
     *
     * @var bool|null
     */
    private $isAutoIncrement;

    /**
     * A value that indicates whether an integer column is signed.
     *
     * @var bool|null
     */
    private $isSigned;

    /**
     * A value that indicates whether the column contains currency values.
     *
     * @var bool|null
     */
    private $isCurrency;

    /**
     * A value that indicates whether the column is case-sensitive.
     *
     * @var bool|null
     */
    private $isCaseSensitive;

    /**
     * A value that indicates whether the column is nullable.
     *
     * @var int|null
     */
    private $nullable;

    /**
     * The precision value of a decimal number column.
     *
     * @var int|null
     */
    private $precision;

    /**
     * The scale value of a decimal number column.
     *
     * @var int|null
     */
    private $scale;

    /**
     * The type of the column.
     *
     * @var int|null
     */
    private $arrayBaseColumnType;

    /**
     * @param array{
     *   name?: string|null,
     *   type?: int|null,
     *   typeName?: string|null,
     *   label?: string|null,
     *   schemaName?: string|null,
     *   tableName?: string|null,
     *   isAutoIncrement?: bool|null,
     *   isSigned?: bool|null,
     *   isCurrency?: bool|null,
     *   isCaseSensitive?: bool|null,
     *   nullable?: int|null,
     *   precision?: int|null,
     *   scale?: int|null,
     *   arrayBaseColumnType?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
        $this->type = $input['type'] ?? null;
        $this->typeName = $input['typeName'] ?? null;
        $this->label = $input['label'] ?? null;
        $this->schemaName = $input['schemaName'] ?? null;
        $this->tableName = $input['tableName'] ?? null;
        $this->isAutoIncrement = $input['isAutoIncrement'] ?? null;
        $this->isSigned = $input['isSigned'] ?? null;
        $this->isCurrency = $input['isCurrency'] ?? null;
        $this->isCaseSensitive = $input['isCaseSensitive'] ?? null;
        $this->nullable = $input['nullable'] ?? null;
        $this->precision = $input['precision'] ?? null;
        $this->scale = $input['scale'] ?? null;
        $this->arrayBaseColumnType = $input['arrayBaseColumnType'] ?? null;
    }

    /**
     * @param array{
     *   name?: string|null,
     *   type?: int|null,
     *   typeName?: string|null,
     *   label?: string|null,
     *   schemaName?: string|null,
     *   tableName?: string|null,
     *   isAutoIncrement?: bool|null,
     *   isSigned?: bool|null,
     *   isCurrency?: bool|null,
     *   isCaseSensitive?: bool|null,
     *   nullable?: int|null,
     *   precision?: int|null,
     *   scale?: int|null,
     *   arrayBaseColumnType?: int|null,
     * }|ColumnMetadata $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArrayBaseColumnType(): ?int
    {
        return $this->arrayBaseColumnType;
    }

    public function getIsAutoIncrement(): ?bool
    {
        return $this->isAutoIncrement;
    }

    public function getIsCaseSensitive(): ?bool
    {
        return $this->isCaseSensitive;
    }

    public function getIsCurrency(): ?bool
    {
        return $this->isCurrency;
    }

    public function getIsSigned(): ?bool
    {
        return $this->isSigned;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNullable(): ?int
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

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getTypeName(): ?string
    {
        return $this->typeName;
    }
}
