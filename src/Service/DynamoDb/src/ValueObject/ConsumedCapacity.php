<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * The capacity units consumed by an operation. The data returned includes the total provisioned throughput consumed,
 * along with statistics for the table and any indexes involved in the operation. `ConsumedCapacity` is only returned if
 * the request asked for it. For more information, see Provisioned capacity mode [^1] in the *Amazon DynamoDB Developer
 * Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/provisioned-capacity-mode.html
 */
final class ConsumedCapacity
{
    /**
     * The name of the table that was affected by the operation. If you had specified the Amazon Resource Name (ARN) of a
     * table in the input, you'll see the table ARN in the response.
     *
     * @var string|null
     */
    private $tableName;

    /**
     * The total number of capacity units consumed by the operation.
     *
     * @var float|null
     */
    private $capacityUnits;

    /**
     * The total number of read capacity units consumed by the operation.
     *
     * @var float|null
     */
    private $readCapacityUnits;

    /**
     * The total number of write capacity units consumed by the operation.
     *
     * @var float|null
     */
    private $writeCapacityUnits;

    /**
     * The amount of throughput consumed on the table affected by the operation.
     *
     * @var Capacity|null
     */
    private $table;

    /**
     * The amount of throughput consumed on each local index affected by the operation.
     *
     * @var array<string, Capacity>|null
     */
    private $localSecondaryIndexes;

    /**
     * The amount of throughput consumed on each global index affected by the operation.
     *
     * @var array<string, Capacity>|null
     */
    private $globalSecondaryIndexes;

    /**
     * @param array{
     *   TableName?: string|null,
     *   CapacityUnits?: float|null,
     *   ReadCapacityUnits?: float|null,
     *   WriteCapacityUnits?: float|null,
     *   Table?: Capacity|array|null,
     *   LocalSecondaryIndexes?: array<string, Capacity|array>|null,
     *   GlobalSecondaryIndexes?: array<string, Capacity|array>|null,
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

    /**
     * @param array{
     *   TableName?: string|null,
     *   CapacityUnits?: float|null,
     *   ReadCapacityUnits?: float|null,
     *   WriteCapacityUnits?: float|null,
     *   Table?: Capacity|array|null,
     *   LocalSecondaryIndexes?: array<string, Capacity|array>|null,
     *   GlobalSecondaryIndexes?: array<string, Capacity|array>|null,
     * }|ConsumedCapacity $input
     */
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
