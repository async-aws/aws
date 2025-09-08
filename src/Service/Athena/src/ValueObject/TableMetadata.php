<?php

namespace AsyncAws\Athena\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains metadata for a table.
 */
final class TableMetadata
{
    /**
     * The name of the table.
     *
     * @var string
     */
    private $name;

    /**
     * The time that the table was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $createTime;

    /**
     * The last time the table was accessed.
     *
     * @var \DateTimeImmutable|null
     */
    private $lastAccessTime;

    /**
     * The type of table. In Athena, only `EXTERNAL_TABLE` is supported.
     *
     * @var string|null
     */
    private $tableType;

    /**
     * A list of the columns in the table.
     *
     * @var Column[]|null
     */
    private $columns;

    /**
     * A list of the partition keys in the table.
     *
     * @var Column[]|null
     */
    private $partitionKeys;

    /**
     * A set of custom key/value pairs for table properties.
     *
     * @var array<string, string>|null
     */
    private $parameters;

    /**
     * @param array{
     *   Name: string,
     *   CreateTime?: \DateTimeImmutable|null,
     *   LastAccessTime?: \DateTimeImmutable|null,
     *   TableType?: string|null,
     *   Columns?: array<Column|array>|null,
     *   PartitionKeys?: array<Column|array>|null,
     *   Parameters?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->createTime = $input['CreateTime'] ?? null;
        $this->lastAccessTime = $input['LastAccessTime'] ?? null;
        $this->tableType = $input['TableType'] ?? null;
        $this->columns = isset($input['Columns']) ? array_map([Column::class, 'create'], $input['Columns']) : null;
        $this->partitionKeys = isset($input['PartitionKeys']) ? array_map([Column::class, 'create'], $input['PartitionKeys']) : null;
        $this->parameters = $input['Parameters'] ?? null;
    }

    /**
     * @param array{
     *   Name: string,
     *   CreateTime?: \DateTimeImmutable|null,
     *   LastAccessTime?: \DateTimeImmutable|null,
     *   TableType?: string|null,
     *   Columns?: array<Column|array>|null,
     *   PartitionKeys?: array<Column|array>|null,
     *   Parameters?: array<string, string>|null,
     * }|TableMetadata $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
