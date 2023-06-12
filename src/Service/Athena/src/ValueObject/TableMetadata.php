<?php

namespace AsyncAws\Athena\ValueObject;

/**
 * Contains metadata for a table.
 */
final class TableMetadata
{
    /**
     * The name of the table.
     */
    private $name;

    /**
     * The time that the table was created.
     */
    private $createTime;

    /**
     * The last time the table was accessed.
     */
    private $lastAccessTime;

    /**
     * The type of table. In Athena, only `EXTERNAL_TABLE` is supported.
     */
    private $tableType;

    /**
     * A list of the columns in the table.
     */
    private $columns;

    /**
     * A list of the partition keys in the table.
     */
    private $partitionKeys;

    /**
     * A set of custom key/value pairs for table properties.
     */
    private $parameters;

    /**
     * @param array{
     *   Name: string,
     *   CreateTime?: null|\DateTimeImmutable,
     *   LastAccessTime?: null|\DateTimeImmutable,
     *   TableType?: null|string,
     *   Columns?: null|Column[],
     *   PartitionKeys?: null|Column[],
     *   Parameters?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? null;
        $this->createTime = $input['CreateTime'] ?? null;
        $this->lastAccessTime = $input['LastAccessTime'] ?? null;
        $this->tableType = $input['TableType'] ?? null;
        $this->columns = isset($input['Columns']) ? array_map([Column::class, 'create'], $input['Columns']) : null;
        $this->partitionKeys = isset($input['PartitionKeys']) ? array_map([Column::class, 'create'], $input['PartitionKeys']) : null;
        $this->parameters = $input['Parameters'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns ?? [];
    }

    public function getCreateTime(): ?\DateTimeImmutable
    {
        return $this->createTime;
    }

    public function getLastAccessTime(): ?\DateTimeImmutable
    {
        return $this->lastAccessTime;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, string>
     */
    public function getParameters(): array
    {
        return $this->parameters ?? [];
    }

    /**
     * @return Column[]
     */
    public function getPartitionKeys(): array
    {
        return $this->partitionKeys ?? [];
    }

    public function getTableType(): ?string
    {
        return $this->tableType;
    }
}
