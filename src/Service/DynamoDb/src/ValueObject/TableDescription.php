<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\TableStatus;

/**
 * Represents the properties of the table.
 */
final class TableDescription
{
    /**
     * An array of `AttributeDefinition` objects. Each of these objects describes one attribute in the table and index key
     * schema.
     */
    private $attributeDefinitions;

    /**
     * The name of the table.
     */
    private $tableName;

    /**
     * The primary key structure for the table. Each `KeySchemaElement` consists of:.
     */
    private $keySchema;

    /**
     * The current state of the table:.
     */
    private $tableStatus;

    /**
     * The date and time when the table was created, in UNIX epoch time format.
     *
     * @see http://www.epochconverter.com/
     */
    private $creationDateTime;

    /**
     * The provisioned throughput settings for the table, consisting of read and write capacity units, along with data about
     * increases and decreases.
     */
    private $provisionedThroughput;

    /**
     * The total size of the specified table, in bytes. DynamoDB updates this value approximately every six hours. Recent
     * changes might not be reflected in this value.
     */
    private $tableSizeBytes;

    /**
     * The number of items in the specified table. DynamoDB updates this value approximately every six hours. Recent changes
     * might not be reflected in this value.
     */
    private $itemCount;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the table.
     */
    private $tableArn;

    /**
     * Unique identifier for the table for which the backup was created.
     */
    private $tableId;

    /**
     * Contains the details for the read/write capacity mode.
     */
    private $billingModeSummary;

    /**
     * Represents one or more local secondary indexes on the table. Each index is scoped to a given partition key value.
     * Tables with one or more local secondary indexes are subject to an item collection size limit, where the amount of
     * data within a given item collection cannot exceed 10 GB. Each element is composed of:.
     */
    private $localSecondaryIndexes;

    /**
     * The global secondary indexes, if any, on the table. Each index is scoped to a given partition key value. Each element
     * is composed of:.
     */
    private $globalSecondaryIndexes;

    /**
     * The current DynamoDB Streams configuration for the table.
     */
    private $streamSpecification;

    /**
     * A timestamp, in ISO 8601 format, for this stream.
     */
    private $latestStreamLabel;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the latest stream for this table.
     */
    private $latestStreamArn;

    /**
     * Represents the version of global tables in use, if the table is replicated across Amazon Web Services Regions.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GlobalTables.html
     */
    private $globalTableVersion;

    /**
     * Represents replicas of the table.
     */
    private $replicas;

    /**
     * Contains details for the restore.
     */
    private $restoreSummary;

    /**
     * The description of the server-side encryption status on the specified table.
     */
    private $sseDescription;

    /**
     * Contains information about the table archive.
     */
    private $archivalSummary;

    /**
     * @param array{
     *   AttributeDefinitions?: null|AttributeDefinition[],
     *   TableName?: null|string,
     *   KeySchema?: null|KeySchemaElement[],
     *   TableStatus?: null|TableStatus::*,
     *   CreationDateTime?: null|\DateTimeImmutable,
     *   ProvisionedThroughput?: null|ProvisionedThroughputDescription|array,
     *   TableSizeBytes?: null|string,
     *   ItemCount?: null|string,
     *   TableArn?: null|string,
     *   TableId?: null|string,
     *   BillingModeSummary?: null|BillingModeSummary|array,
     *   LocalSecondaryIndexes?: null|LocalSecondaryIndexDescription[],
     *   GlobalSecondaryIndexes?: null|GlobalSecondaryIndexDescription[],
     *   StreamSpecification?: null|StreamSpecification|array,
     *   LatestStreamLabel?: null|string,
     *   LatestStreamArn?: null|string,
     *   GlobalTableVersion?: null|string,
     *   Replicas?: null|ReplicaDescription[],
     *   RestoreSummary?: null|RestoreSummary|array,
     *   SSEDescription?: null|SSEDescription|array,
     *   ArchivalSummary?: null|ArchivalSummary|array,
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
    }

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

    public function getItemCount(): ?string
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

    public function getTableId(): ?string
    {
        return $this->tableId;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getTableSizeBytes(): ?string
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
