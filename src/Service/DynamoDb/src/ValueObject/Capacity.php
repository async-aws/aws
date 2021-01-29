<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * The amount of throughput consumed on the table affected by the operation.
 */
final class Capacity
{
    /**
     * The total number of read capacity units consumed on a table or an index.
     */
    private $readCapacityUnits;

    /**
     * The total number of write capacity units consumed on a table or an index.
     */
    private $writeCapacityUnits;

    /**
     * The total number of capacity units consumed on a table or an index.
     */
    private $capacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits?: null|float,
     *   WriteCapacityUnits?: null|float,
     *   CapacityUnits?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->writeCapacityUnits = $input['WriteCapacityUnits'] ?? null;
        $this->capacityUnits = $input['CapacityUnits'] ?? null;
    }

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
