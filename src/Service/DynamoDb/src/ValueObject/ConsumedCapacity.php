<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * The capacity units consumed by an operation. The data returned includes the total provisioned throughput consumed,
 * along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only returned if
 * the request asked for it. For more information, see Provisioned Throughput in the *Amazon DynamoDB Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughputIntro.html
 */
final class ConsumedCapacity
{
    /**
     * The name of the table that was affected by the operation.
     */
    private $TableName;

    /**
     * The total number of capacity units consumed by the operation.
     */
    private $CapacityUnits;

    /**
     * The total number of read capacity units consumed by the operation.
     */
    private $ReadCapacityUnits;

    /**
     * The total number of write capacity units consumed by the operation.
     */
    private $WriteCapacityUnits;

    /**
     * The amount of throughput consumed on the table affected by the operation.
     */
    private $Table;

    /**
     * The amount of throughput consumed on each local index affected by the operation.
     */
    private $LocalSecondaryIndexes;

    /**
     * The amount of throughput consumed on each global index affected by the operation.
     */
    private $GlobalSecondaryIndexes;

    /**
     * @param array{
     *   TableName?: null|string,
     *   CapacityUnits?: null|float,
     *   ReadCapacityUnits?: null|float,
     *   WriteCapacityUnits?: null|float,
     *   Table?: null|Capacity|array,
     *   LocalSecondaryIndexes?: null|array<string, Capacity>,
     *   GlobalSecondaryIndexes?: null|array<string, Capacity>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->TableName = $input['TableName'] ?? null;
        $this->CapacityUnits = $input['CapacityUnits'] ?? null;
        $this->ReadCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->WriteCapacityUnits = $input['WriteCapacityUnits'] ?? null;
        $this->Table = isset($input['Table']) ? Capacity::create($input['Table']) : null;
        $this->LocalSecondaryIndexes = isset($input['LocalSecondaryIndexes']) ? array_map([Capacity::class, 'create'], $input['LocalSecondaryIndexes']) : null;
        $this->GlobalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([Capacity::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCapacityUnits(): ?float
    {
        return $this->CapacityUnits;
    }

    /**
     * @return array<string, Capacity>
     */
    public function getGlobalSecondaryIndexes(): array
    {
        return $this->GlobalSecondaryIndexes ?? [];
    }

    /**
     * @return array<string, Capacity>
     */
    public function getLocalSecondaryIndexes(): array
    {
        return $this->LocalSecondaryIndexes ?? [];
    }

    public function getReadCapacityUnits(): ?float
    {
        return $this->ReadCapacityUnits;
    }

    public function getTable(): ?Capacity
    {
        return $this->Table;
    }

    public function getTableName(): ?string
    {
        return $this->TableName;
    }

    public function getWriteCapacityUnits(): ?float
    {
        return $this->WriteCapacityUnits;
    }
}
