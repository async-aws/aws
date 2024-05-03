<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\TableStatus;

/**
 * Represents the properties of a table.
 */
final class TableDescription
{
    /**
     * An array of `AttributeDefinition` objects. Each of these objects describes one attribute in the table and index key
     * schema.
     *
     * Each `AttributeDefinition` object in this array is composed of:
     *
     * - `AttributeName` - The name of the attribute.
     * - `AttributeType` - The data type for the attribute.
     *
     * @var AttributeDefinition[]|null
     */
    private $attributeDefinitions;

    /**
     * The name of the table.
     *
     * @var string|null
     */
    private $tableName;

    /**
     * The primary key structure for the table. Each `KeySchemaElement` consists of:
     *
     * - `AttributeName` - The name of the attribute.
     * - `KeyType` - The role of the attribute:
     *
     *   - `HASH` - partition key
     *   - `RANGE` - sort key
     *
     *   > The partition key of an item is also known as its *hash attribute*. The term "hash attribute" derives from
     *   > DynamoDB's usage of an internal hash function to evenly distribute data items across partitions, based on their
     *   > partition key values.
     *   >
     *   > The sort key of an item is also known as its *range attribute*. The term "range attribute" derives from the way
     *   > DynamoDB stores items with the same partition key physically close together, in sorted order by the sort key
     *   > value.
     *
     *
     * For more information about primary keys, see Primary Key [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DataModel.html#DataModelPrimaryKey
     *
     * @var KeySchemaElement[]|null
     */
    private $keySchema;

    /**
     * The current state of the table:
     *
     * - `CREATING` - The table is being created.
     * - `UPDATING` - The table/index configuration is being updated. The table/index remains available for data operations
     *   when `UPDATING`.
     * - `DELETING` - The table is being deleted.
     * - `ACTIVE` - The table is ready for use.
     * - `INACCESSIBLE_ENCRYPTION_CREDENTIALS` - The KMS key used to encrypt the table in inaccessible. Table operations may
     *   fail due to failure to use the KMS key. DynamoDB will initiate the table archival process when a table's KMS key
     *   remains inaccessible for more than seven days.
     * - `ARCHIVING` - The table is being archived. Operations are not allowed until archival is complete.
     * - `ARCHIVED` - The table has been archived. See the ArchivalReason for more information.
     *
     * @var TableStatus::*|null
     */
    private $tableStatus;

    /**
     * The date and time when the table was created, in UNIX epoch time [^1] format.
     *
     * [^1]: http://www.epochconverter.com/
     *
     * @var \DateTimeImmutable|null
     */
    private $creationDateTime;

    /**
     * The provisioned throughput settings for the table, consisting of read and write capacity units, along with data about
     * increases and decreases.
     *
     * @var ProvisionedThroughputDescription|null
     */
    private $provisionedThroughput;

    /**
     * The total size of the specified table, in bytes. DynamoDB updates this value approximately every six hours. Recent
     * changes might not be reflected in this value.
     *
     * @var int|null
     */
    private $tableSizeBytes;

    /**
     * The number of items in the specified table. DynamoDB updates this value approximately every six hours. Recent changes
     * might not be reflected in this value.
     *
     * @var int|null
     */
    private $itemCount;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the table.
     *
     * @var string|null
     */
    private $tableArn;

    /**
     * Unique identifier for the table for which the backup was created.
     *
     * @var string|null
     */
    private $tableId;

    /**
     * Contains the details for the read/write capacity mode.
     *
     * @var BillingModeSummary|null
     */
    private $billingModeSummary;

    /**
     * Represents one or more local secondary indexes on the table. Each index is scoped to a given partition key value.
     * Tables with one or more local secondary indexes are subject to an item collection size limit, where the amount of
     * data within a given item collection cannot exceed 10 GB. Each element is composed of:
     *
     * - `IndexName` - The name of the local secondary index.
     * - `KeySchema` - Specifies the complete index key schema. The attribute names in the key schema must be between 1 and
     *   255 characters (inclusive). The key schema must begin with the same partition key as the table.
     * - `Projection` - Specifies attributes that are copied (projected) from the table into the index. These are in
     *   addition to the primary key attributes and index key attributes, which are automatically projected. Each attribute
     *   specification is composed of:
     *
     *   - `ProjectionType` - One of the following:
     *
     *     - `KEYS_ONLY` - Only the index and primary keys are projected into the index.
     *     - `INCLUDE` - Only the specified table attributes are projected into the index. The list of projected attributes
     *       is in `NonKeyAttributes`.
     *     - `ALL` - All of the table attributes are projected into the index.
     *
     *   - `NonKeyAttributes` - A list of one or more non-key attribute names that are projected into the secondary index.
     *     The total count of attributes provided in `NonKeyAttributes`, summed across all of the secondary indexes, must
     *     not exceed 100. If you project the same attribute into two different indexes, this counts as two distinct
     *     attributes when determining the total.
     *
     * - `IndexSizeBytes` - Represents the total size of the index, in bytes. DynamoDB updates this value approximately
     *   every six hours. Recent changes might not be reflected in this value.
     * - `ItemCount` - Represents the number of items in the index. DynamoDB updates this value approximately every six
     *   hours. Recent changes might not be reflected in this value.
     *
     * If the table is in the `DELETING` state, no information about indexes will be returned.
     *
     * @var LocalSecondaryIndexDescription[]|null
     */
    private $localSecondaryIndexes;

    /**
     * The global secondary indexes, if any, on the table. Each index is scoped to a given partition key value. Each element
     * is composed of:
     *
     * - `Backfilling` - If true, then the index is currently in the backfilling phase. Backfilling occurs only when a new
     *   global secondary index is added to the table. It is the process by which DynamoDB populates the new index with data
     *   from the table. (This attribute does not appear for indexes that were created during a `CreateTable` operation.)
     *
     *   You can delete an index that is being created during the `Backfilling` phase when `IndexStatus` is set to CREATING
     *   and `Backfilling` is true. You can't delete the index that is being created when `IndexStatus` is set to CREATING
     *   and `Backfilling` is false. (This attribute does not appear for indexes that were created during a `CreateTable`
     *   operation.)
     * - `IndexName` - The name of the global secondary index.
     * - `IndexSizeBytes` - The total size of the global secondary index, in bytes. DynamoDB updates this value
     *   approximately every six hours. Recent changes might not be reflected in this value.
     * - `IndexStatus` - The current status of the global secondary index:
     *
     *   - `CREATING` - The index is being created.
     *   - `UPDATING` - The index is being updated.
     *   - `DELETING` - The index is being deleted.
     *   - `ACTIVE` - The index is ready for use.
     *
     * - `ItemCount` - The number of items in the global secondary index. DynamoDB updates this value approximately every
     *   six hours. Recent changes might not be reflected in this value.
     * - `KeySchema` - Specifies the complete index key schema. The attribute names in the key schema must be between 1 and
     *   255 characters (inclusive). The key schema must begin with the same partition key as the table.
     * - `Projection` - Specifies attributes that are copied (projected) from the table into the index. These are in
     *   addition to the primary key attributes and index key attributes, which are automatically projected. Each attribute
     *   specification is composed of:
     *
     *   - `ProjectionType` - One of the following:
     *
     *     - `KEYS_ONLY` - Only the index and primary keys are projected into the index.
     *     - `INCLUDE` - In addition to the attributes described in `KEYS_ONLY`, the secondary index will include other
     *       non-key attributes that you specify.
     *     - `ALL` - All of the table attributes are projected into the index.
     *
     *   - `NonKeyAttributes` - A list of one or more non-key attribute names that are projected into the secondary index.
     *     The total count of attributes provided in `NonKeyAttributes`, summed across all of the secondary indexes, must
     *     not exceed 100. If you project the same attribute into two different indexes, this counts as two distinct
     *     attributes when determining the total.
     *
     * - `ProvisionedThroughput` - The provisioned throughput settings for the global secondary index, consisting of read
     *   and write capacity units, along with data about increases and decreases.
     *
     * If the table is in the `DELETING` state, no information about indexes will be returned.
     *
     * @var GlobalSecondaryIndexDescription[]|null
     */
    private $globalSecondaryIndexes;

    /**
     * The current DynamoDB Streams configuration for the table.
     *
     * @var StreamSpecification|null
     */
    private $streamSpecification;

    /**
     * A timestamp, in ISO 8601 format, for this stream.
     *
     * Note that `LatestStreamLabel` is not a unique identifier for the stream, because it is possible that a stream from
     * another table might have the same timestamp. However, the combination of the following three elements is guaranteed
     * to be unique:
     *
     * - Amazon Web Services customer ID
     * - Table name
     * - `StreamLabel`
     *
     * @var string|null
     */
    private $latestStreamLabel;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the latest stream for this table.
     *
     * @var string|null
     */
    private $latestStreamArn;

    /**
     * Represents the version of global tables [^1] in use, if the table is replicated across Amazon Web Services Regions.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GlobalTables.html
     *
     * @var string|null
     */
    private $globalTableVersion;

    /**
     * Represents replicas of the table.
     *
     * @var ReplicaDescription[]|null
     */
    private $replicas;

    /**
     * Contains details for the restore.
     *
     * @var RestoreSummary|null
     */
    private $restoreSummary;

    /**
     * The description of the server-side encryption status on the specified table.
     *
     * @var SSEDescription|null
     */
    private $sseDescription;

    /**
     * Contains information about the table archive.
     *
     * @var ArchivalSummary|null
     */
    private $archivalSummary;

    /**
     * Contains details of the table class.
     *
     * @var TableClassSummary|null
     */
    private $tableClassSummary;

    /**
     * Indicates whether deletion protection is enabled (true) or disabled (false) on the table.
     *
     * @var bool|null
     */
    private $deletionProtectionEnabled;

    /**
     * The maximum number of read and write units for the specified on-demand table. If you use this parameter, you must
     * specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both.
     *
     * @var OnDemandThroughput|null
     */
    private $onDemandThroughput;

    /**
     * @param array{
     *   AttributeDefinitions?: null|array<AttributeDefinition|array>,
     *   TableName?: null|string,
     *   KeySchema?: null|array<KeySchemaElement|array>,
     *   TableStatus?: null|TableStatus::*,
     *   CreationDateTime?: null|\DateTimeImmutable,
     *   ProvisionedThroughput?: null|ProvisionedThroughputDescription|array,
     *   TableSizeBytes?: null|int,
     *   ItemCount?: null|int,
     *   TableArn?: null|string,
     *   TableId?: null|string,
     *   BillingModeSummary?: null|BillingModeSummary|array,
     *   LocalSecondaryIndexes?: null|array<LocalSecondaryIndexDescription|array>,
     *   GlobalSecondaryIndexes?: null|array<GlobalSecondaryIndexDescription|array>,
     *   StreamSpecification?: null|StreamSpecification|array,
     *   LatestStreamLabel?: null|string,
     *   LatestStreamArn?: null|string,
     *   GlobalTableVersion?: null|string,
     *   Replicas?: null|array<ReplicaDescription|array>,
     *   RestoreSummary?: null|RestoreSummary|array,
     *   SSEDescription?: null|SSEDescription|array,
     *   ArchivalSummary?: null|ArchivalSummary|array,
     *   TableClassSummary?: null|TableClassSummary|array,
     *   DeletionProtectionEnabled?: null|bool,
     *   OnDemandThroughput?: null|OnDemandThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attributeDefinitions = isset($input['AttributeDefinitions']) ? array_map([AttributeDefinition::class, 'create'], $input['AttributeDefinitions']) : null;
        $this->tableName = $input['TableName'] ?? null;
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->tableStatus = $input['TableStatus'] ?? null;
        $this->creationDateTime = $input['CreationDateTime'] ?? null;
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughputDescription::create($input['ProvisionedThroughput']) : null;
        $this->tableSizeBytes = $input['TableSizeBytes'] ?? null;
        $this->itemCount = $input['ItemCount'] ?? null;
        $this->tableArn = $input['TableArn'] ?? null;
        $this->tableId = $input['TableId'] ?? null;
        $this->billingModeSummary = isset($input['BillingModeSummary']) ? BillingModeSummary::create($input['BillingModeSummary']) : null;
        $this->localSecondaryIndexes = isset($input['LocalSecondaryIndexes']) ? array_map([LocalSecondaryIndexDescription::class, 'create'], $input['LocalSecondaryIndexes']) : null;
        $this->globalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([GlobalSecondaryIndexDescription::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
        $this->streamSpecification = isset($input['StreamSpecification']) ? StreamSpecification::create($input['StreamSpecification']) : null;
        $this->latestStreamLabel = $input['LatestStreamLabel'] ?? null;
        $this->latestStreamArn = $input['LatestStreamArn'] ?? null;
        $this->globalTableVersion = $input['GlobalTableVersion'] ?? null;
        $this->replicas = isset($input['Replicas']) ? array_map([ReplicaDescription::class, 'create'], $input['Replicas']) : null;
        $this->restoreSummary = isset($input['RestoreSummary']) ? RestoreSummary::create($input['RestoreSummary']) : null;
        $this->sseDescription = isset($input['SSEDescription']) ? SSEDescription::create($input['SSEDescription']) : null;
        $this->archivalSummary = isset($input['ArchivalSummary']) ? ArchivalSummary::create($input['ArchivalSummary']) : null;
        $this->tableClassSummary = isset($input['TableClassSummary']) ? TableClassSummary::create($input['TableClassSummary']) : null;
        $this->deletionProtectionEnabled = $input['DeletionProtectionEnabled'] ?? null;
        $this->onDemandThroughput = isset($input['OnDemandThroughput']) ? OnDemandThroughput::create($input['OnDemandThroughput']) : null;
    }

    /**
     * @param array{
     *   AttributeDefinitions?: null|array<AttributeDefinition|array>,
     *   TableName?: null|string,
     *   KeySchema?: null|array<KeySchemaElement|array>,
     *   TableStatus?: null|TableStatus::*,
     *   CreationDateTime?: null|\DateTimeImmutable,
     *   ProvisionedThroughput?: null|ProvisionedThroughputDescription|array,
     *   TableSizeBytes?: null|int,
     *   ItemCount?: null|int,
     *   TableArn?: null|string,
     *   TableId?: null|string,
     *   BillingModeSummary?: null|BillingModeSummary|array,
     *   LocalSecondaryIndexes?: null|array<LocalSecondaryIndexDescription|array>,
     *   GlobalSecondaryIndexes?: null|array<GlobalSecondaryIndexDescription|array>,
     *   StreamSpecification?: null|StreamSpecification|array,
     *   LatestStreamLabel?: null|string,
     *   LatestStreamArn?: null|string,
     *   GlobalTableVersion?: null|string,
     *   Replicas?: null|array<ReplicaDescription|array>,
     *   RestoreSummary?: null|RestoreSummary|array,
     *   SSEDescription?: null|SSEDescription|array,
     *   ArchivalSummary?: null|ArchivalSummary|array,
     *   TableClassSummary?: null|TableClassSummary|array,
     *   DeletionProtectionEnabled?: null|bool,
     *   OnDemandThroughput?: null|OnDemandThroughput|array,
     * }|TableDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArchivalSummary(): ?ArchivalSummary
    {
        return $this->archivalSummary;
    }

    /**
     * @return AttributeDefinition[]
     */
    public function getAttributeDefinitions(): array
    {
        return $this->attributeDefinitions ?? [];
    }

    public function getBillingModeSummary(): ?BillingModeSummary
    {
        return $this->billingModeSummary;
    }

    public function getCreationDateTime(): ?\DateTimeImmutable
    {
        return $this->creationDateTime;
    }

    public function getDeletionProtectionEnabled(): ?bool
    {
        return $this->deletionProtectionEnabled;
    }

    /**
     * @return GlobalSecondaryIndexDescription[]
     */
    public function getGlobalSecondaryIndexes(): array
    {
        return $this->globalSecondaryIndexes ?? [];
    }

    public function getGlobalTableVersion(): ?string
    {
        return $this->globalTableVersion;
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

    public function getLatestStreamArn(): ?string
    {
        return $this->latestStreamArn;
    }

    public function getLatestStreamLabel(): ?string
    {
        return $this->latestStreamLabel;
    }

    /**
     * @return LocalSecondaryIndexDescription[]
     */
    public function getLocalSecondaryIndexes(): array
    {
        return $this->localSecondaryIndexes ?? [];
    }

    public function getOnDemandThroughput(): ?OnDemandThroughput
    {
        return $this->onDemandThroughput;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughputDescription
    {
        return $this->provisionedThroughput;
    }

    /**
     * @return ReplicaDescription[]
     */
    public function getReplicas(): array
    {
        return $this->replicas ?? [];
    }

    public function getRestoreSummary(): ?RestoreSummary
    {
        return $this->restoreSummary;
    }

    public function getSseDescription(): ?SSEDescription
    {
        return $this->sseDescription;
    }

    public function getStreamSpecification(): ?StreamSpecification
    {
        return $this->streamSpecification;
    }

    public function getTableArn(): ?string
    {
        return $this->tableArn;
    }

    public function getTableClassSummary(): ?TableClassSummary
    {
        return $this->tableClassSummary;
    }

    public function getTableId(): ?string
    {
        return $this->tableId;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getTableSizeBytes(): ?int
    {
        return $this->tableSizeBytes;
    }

    /**
     * @return TableStatus::*|null
     */
    public function getTableStatus(): ?string
    {
        return $this->tableStatus;
    }
}
