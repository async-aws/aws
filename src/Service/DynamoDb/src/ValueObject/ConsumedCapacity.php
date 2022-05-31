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
    private $tableName;

    /**
     * The total number of capacity units consumed by the operation.
     */
    private $capacityUnits;

    /**
     * The total number of read capacity units consumed by the operation.
     */
    private $readCapacityUnits;

    /**
     * The total number of write capacity units consumed by the operation.
     */
    private $writeCapacityUnits;

    /**
     * The amount of throughput consumed on the table affected by the operation.
     */
    private $table;

    /**
     * The amount of throughput consumed on each local index affected by the operation.
     */
    private $localSecondaryIndexes;

    /**
     * The amount of throughput consumed on each global index affected by the operation.
     */
    private $globalSecondaryIndexes;

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
        $this->tableName = $input['TableName'] ?? null;
        $this->capacityUnits = $input['CapacityUnits'] ?? null;
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->writeCapacityUnits = $input['WriteCapacityUnits'] ?? null;
        $this->table = isset($input['Table']) ? Capacity::create($input['Table']) : null;
        $this->localSecondaryIndexes = isset($input['LocalSecondaryIndexes']) ? array_map([Capacity::class, 'create'], $input['LocalSecondaryIndexes']) : null;
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([Capacity::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCapacityUnits(): ?float
    {
        return $this->capacityUnits;
    }

    /**
     * @return array<string, Capacity>
     */
    public function getGlobalSecondaryIndexes(): array
    {
        return $this->globalSecondaryIndexes ?? [];
    }

    /**
     * @return array<string, Capacity>
     */
    public function getLocalSecondaryIndexes(): array
    {
        return $this->localSecondaryIndexes ?? [];
    }

    public function getReadCapacityUnits(): ?float
    {
        return $this->readCapacityUnits;
    }

    public function getTable(): ?Capacity
    {
        return $this->table;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getWriteCapacityUnits(): ?float
    {
        return $this->writeCapacityUnits;
    }
}
