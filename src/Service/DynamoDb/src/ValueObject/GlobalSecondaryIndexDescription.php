<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\IndexStatus;

/**
 * Represents the properties of a global secondary index.
 */
final class GlobalSecondaryIndexDescription
{
    /**
     * The name of the global secondary index.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The complete key schema for a global secondary index, which consists of one or more pairs of attribute names and key
     * types:
     *
     * - `HASH` - partition key
     * - `RANGE` - sort key
     *
     * > The partition key of an item is also known as its *hash attribute*. The term "hash attribute" derives from
     * > DynamoDB's usage of an internal hash function to evenly distribute data items across partitions, based on their
     * > partition key values.
     * >
     * > The sort key of an item is also known as its *range attribute*. The term "range attribute" derives from the way
     * > DynamoDB stores items with the same partition key physically close together, in sorted order by the sort key value.
     *
     * @var KeySchemaElement[]|null
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the global secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     *
     * @var Projection|null
     */
    private $projection;

    /**
     * The current state of the global secondary index:
     *
     * - `CREATING` - The index is being created.
     * - `UPDATING` - The index is being updated.
     * - `DELETING` - The index is being deleted.
     * - `ACTIVE` - The index is ready for use.
     *
     * @var IndexStatus::*|null
     */
    private $indexStatus;

    /**
     * Indicates whether the index is currently backfilling. *Backfilling* is the process of reading items from the table
     * and determining whether they can be added to the index. (Not all items will qualify: For example, a partition key
     * cannot have any duplicate values.) If an item can be added to the index, DynamoDB will do so. After all items have
     * been processed, the backfilling operation is complete and `Backfilling` is false.
     *
     * You can delete an index that is being created during the `Backfilling` phase when `IndexStatus` is set to CREATING
     * and `Backfilling` is true. You can't delete the index that is being created when `IndexStatus` is set to CREATING and
     * `Backfilling` is false.
     *
     * > For indexes that were created during a `CreateTable` operation, the `Backfilling` attribute does not appear in the
     * > `DescribeTable` output.
     *
     * @var bool|null
     */
    private $backfilling;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     *
     * For current minimum and maximum provisioned throughput values, see Service, Account, and Table Quotas [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     *
     * @var ProvisionedThroughputDescription|null
     */
    private $provisionedThroughput;

    /**
     * The total size of the specified index, in bytes. DynamoDB updates this value approximately every six hours. Recent
     * changes might not be reflected in this value.
     *
     * @var int|null
     */
    private $indexSizeBytes;

    /**
     * The number of items in the specified index. DynamoDB updates this value approximately every six hours. Recent changes
     * might not be reflected in this value.
     *
     * @var int|null
     */
    private $itemCount;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the index.
     *
     * @var string|null
     */
    private $indexArn;

    /**
     * The maximum number of read and write units for the specified global secondary index. If you use this parameter, you
     * must specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both.
     *
     * @var OnDemandThroughput|null
     */
    private $onDemandThroughput;

    /**
     * Represents the warm throughput value (in read units per second and write units per second) for the specified
     * secondary index.
     *
     * @var GlobalSecondaryIndexWarmThroughputDescription|null
     */
    private $warmThroughput;

    /**
     * @param array{
     *   IndexName?: string|null,
     *   KeySchema?: array<KeySchemaElement|array>|null,
     *   Projection?: Projection|array|null,
     *   IndexStatus?: IndexStatus::*|null,
     *   Backfilling?: bool|null,
     *   ProvisionedThroughput?: ProvisionedThroughputDescription|array|null,
     *   IndexSizeBytes?: int|null,
     *   ItemCount?: int|null,
     *   IndexArn?: string|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   WarmThroughput?: GlobalSecondaryIndexWarmThroughputDescription|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
        $this->indexStatus = $input['IndexStatus'] ?? null;
        $this->backfilling = $input['Backfilling'] ?? null;
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughputDescription::create($input['ProvisionedThroughput']) : null;
        $this->indexSizeBytes = $input['IndexSizeBytes'] ?? null;
        $this->itemCount = $input['ItemCount'] ?? null;
        $this->indexArn = $input['IndexArn'] ?? null;
        $this->onDemandThroughput = isset($input['OnDemandThroughput']) ? OnDemandThroughput::create($input['OnDemandThroughput']) : null;
        $this->warmThroughput = isset($input['WarmThroughput']) ? GlobalSecondaryIndexWarmThroughputDescription::create($input['WarmThroughput']) : null;
    }

    /**
     * @param array{
     *   IndexName?: string|null,
     *   KeySchema?: array<KeySchemaElement|array>|null,
     *   Projection?: Projection|array|null,
     *   IndexStatus?: IndexStatus::*|null,
     *   Backfilling?: bool|null,
     *   ProvisionedThroughput?: ProvisionedThroughputDescription|array|null,
     *   IndexSizeBytes?: int|null,
     *   ItemCount?: int|null,
     *   IndexArn?: string|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   WarmThroughput?: GlobalSecondaryIndexWarmThroughputDescription|array|null,
     * }|GlobalSecondaryIndexDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBackfilling(): ?bool
    {
        return $this->backfilling;
    }

    public function getIndexArn(): ?string
    {
        return $this->indexArn;
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getIndexSizeBytes(): ?int
    {
        return $this->indexSizeBytes;
    }

    /**
     * @return IndexStatus::*|null
     */
    public function getIndexStatus(): ?string
    {
        return $this->indexStatus;
    }

    public function getItemCount(): ?int
    {
        return $this->itemCount;
    }

    /**
     * @return KeySchemaElement[]
     */
    public function getKeySchema(): array
    {
        return $this->keySchema ?? [];
    }

    public function getOnDemandThroughput(): ?OnDemandThroughput
    {
        return $this->onDemandThroughput;
    }

    public function getProjection(): ?Projection
    {
        return $this->projection;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughputDescription
    {
        return $this->provisionedThroughput;
    }

    public function getWarmThroughput(): ?GlobalSecondaryIndexWarmThroughputDescription
    {
        return $this->warmThroughput;
    }
}
