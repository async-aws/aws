<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\TableStatus;

/**
 * Represents the warm throughput value (in read units per second and write units per second) of the table. Warm
 * throughput is applicable for DynamoDB Standard-IA tables and specifies the minimum provisioned capacity maintained
 * for immediate data access.
 */
final class TableWarmThroughputDescription
{
    /**
     * Represents the base table's warm throughput value in read units per second.
     *
     * @var int|null
     */
    private $readUnitsPerSecond;

    /**
     * Represents the base table's warm throughput value in write units per second.
     *
     * @var int|null
     */
    private $writeUnitsPerSecond;

    /**
     * Represents warm throughput value of the base table.
     *
     * @var TableStatus::*|null
     */
    private $status;

    /**
     * @param array{
     *   ReadUnitsPerSecond?: int|null,
     *   WriteUnitsPerSecond?: int|null,
     *   Status?: TableStatus::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readUnitsPerSecond = $input['ReadUnitsPerSecond'] ?? null;
        $this->writeUnitsPerSecond = $input['WriteUnitsPerSecond'] ?? null;
        $this->status = $input['Status'] ?? null;
    }

    /**
     * @param array{
     *   ReadUnitsPerSecond?: int|null,
     *   WriteUnitsPerSecond?: int|null,
     *   Status?: TableStatus::*|null,
     * }|TableWarmThroughputDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReadUnitsPerSecond(): ?int
    {
        return $this->readUnitsPerSecond;
    }

    /**
     * @return TableStatus::*|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getWriteUnitsPerSecond(): ?int
    {
        return $this->writeUnitsPerSecond;
    }
}
