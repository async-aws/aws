<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\IndexStatus;

/**
 * The description of the warm throughput value on a global secondary index.
 */
final class GlobalSecondaryIndexWarmThroughputDescription
{
    /**
     * Represents warm throughput read units per second value for a global secondary index.
     *
     * @var int|null
     */
    private $readUnitsPerSecond;

    /**
     * Represents warm throughput write units per second value for a global secondary index.
     *
     * @var int|null
     */
    private $writeUnitsPerSecond;

    /**
     * Represents the warm throughput status being created or updated on a global secondary index. The status can only be
     * `UPDATING` or `ACTIVE`.
     *
     * @var IndexStatus::*|string|null
     */
    private $status;

    /**
     * @param array{
     *   ReadUnitsPerSecond?: null|int,
     *   WriteUnitsPerSecond?: null|int,
     *   Status?: null|IndexStatus::*|string,
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
     *   Status?: null|IndexStatus::*|string,
     * }|GlobalSecondaryIndexWarmThroughputDescription $input
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
     * @return IndexStatus::*|string|null
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
