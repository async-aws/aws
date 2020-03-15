<?php

namespace AsyncAws\DynamoDb\ValueObject;

class Capacity
{
    /**
     * The total number of read capacity units consumed on a table or an index.
     */
    private $ReadCapacityUnits;

    /**
     * The total number of write capacity units consumed on a table or an index.
     */
    private $WriteCapacityUnits;

    /**
     * The total number of capacity units consumed on a table or an index.
     */
    private $CapacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits?: null|float,
     *   WriteCapacityUnits?: null|float,
     *   CapacityUnits?: null|float,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ReadCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->WriteCapacityUnits = $input['WriteCapacityUnits'] ?? null;
        $this->CapacityUnits = $input['CapacityUnits'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCapacityUnits(): ?float
    {
        return $this->CapacityUnits;
    }

    public function getReadCapacityUnits(): ?float
    {
        return $this->ReadCapacityUnits;
    }

    public function getWriteCapacityUnits(): ?float
    {
        return $this->WriteCapacityUnits;
    }
}
