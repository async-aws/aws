<?php

namespace AsyncAws\TimestreamQuery\ValueObject;

/**
 * Details of the column that is returned by the query.
 */
final class SelectColumn
{
    /**
     * Name of the column.
     *
     * @var string|null
     */
    private $name;

    /**
     * @var Type|null
     */
    private $type;

    /**
     * Database that has this column.
     *
     * @var string|null
     */
    private $databaseName;

    /**
     * Table within the database that has this column.
     *
     * @var string|null
     */
    private $tableName;

    /**
     * True, if the column name was aliased by the query. False otherwise.
     *
     * @var bool|null
     */
    private $aliased;

    /**
     * @param array{
     *   Name?: string|null,
     *   Type?: Type|array|null,
     *   DatabaseName?: string|null,
     *   TableName?: string|null,
     *   Aliased?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->type = isset($input['Type']) ? Type::create($input['Type']) : null;
        $this->databaseName = $input['DatabaseName'] ?? null;
        $this->tableName = $input['TableName'] ?? null;
        $this->aliased = $input['Aliased'] ?? null;
    }

    /**
     * @param array{
     *   Name?: string|null,
     *   Type?: Type|array|null,
     *   DatabaseName?: string|null,
     *   TableName?: string|null,
     *   Aliased?: bool|null,
     * }|SelectColumn $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAliased(): ?bool
    {
        return $this->aliased;
    }

    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }
}
