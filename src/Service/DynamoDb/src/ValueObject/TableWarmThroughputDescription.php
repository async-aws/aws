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
     * @var TableStatus::*|string|null
     */
    private $status;

    /**
     * @param array{
     *   ReadUnitsPerSecond?: null|int,
     *   WriteUnitsPerSecond?: null|int,
     *   Status?: null|TableStatus::*|string,
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
     *   ReadUnitsPerSecond?: null|int,
     *   WriteUnitsPerSecond?: null|int,
     *   Status?: null|TableStatus::*|string,
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
     * @return TableStatus::*|string|null
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
