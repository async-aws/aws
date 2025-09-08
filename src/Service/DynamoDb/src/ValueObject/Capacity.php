<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the amount of provisioned throughput capacity consumed on a table or an index.
 */
final class Capacity
{
    /**
     * The total number of read capacity units consumed on a table or an index.
     *
     * @var float|null
     */
    private $readCapacityUnits;

    /**
     * The total number of write capacity units consumed on a table or an index.
     *
     * @var float|null
     */
    private $writeCapacityUnits;

    /**
     * The total number of capacity units consumed on a table or an index.
     *
     * @var float|null
     */
    private $capacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits?: float|null,
     *   WriteCapacityUnits?: float|null,
     *   CapacityUnits?: float|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->writeCapacityUnits = $input['WriteCapacityUnits'] ?? null;
        $this->capacityUnits = $input['CapacityUnits'] ?? null;
    }

    /**
     * @param array{
     *   ReadCapacityUnits?: float|null,
     *   WriteCapacityUnits?: float|null,
     *   CapacityUnits?: float|null,
     * }|Capacity $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCapacityUnits(): ?float
    {
        return $this->capacityUnits;
    }

    public function getReadCapacityUnits(): ?float
    {
        return $this->readCapacityUnits;
    }

    public function getWriteCapacityUnits(): ?float
    {
        return $this->writeCapacityUnits;
    }
}
