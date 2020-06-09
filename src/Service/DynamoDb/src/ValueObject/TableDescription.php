<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\TableStatus;

final class TableDescription
{
    /**
     * An array of `AttributeDefinition` objects. Each of these objects describes one attribute in the table and index key
     * schema.
     */
    private $AttributeDefinitions;

    /**
     * The name of the table.
     */
    private $TableName;

    /**
     * The primary key structure for the table. Each `KeySchemaElement` consists of:.
     */
    private $KeySchema;

    /**
     * The current state of the table:.
     */
    private $TableStatus;

    /**
     * The date and time when the table was created, in UNIX epoch time format.
     *
     * @see http://www.epochconverter.com/
     */
    private $CreationDateTime;

    /**
     * The provisioned throughput settings for the table, consisting of read and write capacity units, along with data about
     * increases and decreases.
     */
    private $ProvisionedThroughput;

    /**
     * The total size of the specified table, in bytes. DynamoDB updates this value approximately every six hours. Recent
     * changes might not be reflected in this value.
     */
    private $TableSizeBytes;

    /**
     * The number of items in the specified table. DynamoDB updates this value approximately every six hours. Recent changes
     * might not be reflected in this value.
     */
    private $ItemCount;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the table.
     */
    private $TableArn;

    /**
     * Unique identifier for the table for which the backup was created.
     */
    private $TableId;

    /**
     * Contains the details for the read/write capacity mode.
     */
    private $BillingModeSummary;

    /**
     * Represents one or more local secondary indexes on the table. Each index is scoped to a given partition key value.
     * Tables with one or more local secondary indexes are subject to an item collection size limit, where the amount of
     * data within a given item collection cannot exceed 10 GB. Each element is composed of:.
     */
    private $LocalSecondaryIndexes;

    /**
     * The global secondary indexes, if any, on the table. Each index is scoped to a given partition key value. Each element
     * is composed of:.
     */
    private $GlobalSecondaryIndexes;

    /**
     * The current DynamoDB Streams configuration for the table.
     */
    private $StreamSpecification;

    /**
     * A timestamp, in ISO 8601 format, for this stream.
     */
    private $LatestStreamLabel;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the latest stream for this table.
     */
    private $LatestStreamArn;

    /**
     * Represents the version of global tables in use, if the table is replicated across AWS Regions.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/GlobalTables.html
     */
    private $GlobalTableVersion;

    /**
     * Represents replicas of the table.
     */
    private $Replicas;

    /**
     * Contains details for the restore.
     */
    private $RestoreSummary;

    /**
     * The description of the server-side encryption status on the specified table.
     */
    private $SSEDescription;

    /**
     * Contains information about the table archive.
     */
    private $ArchivalSummary;

    /**
     * @param array{
     *   AttributeDefinitions?: null|\AsyncAws\DynamoDb\ValueObject\AttributeDefinition[],
     *   TableName?: null|string,
     *   KeySchema?: null|\AsyncAws\DynamoDb\ValueObject\KeySchemaElement[],
     *   TableStatus?: null|\AsyncAws\DynamoDb\Enum\TableStatus::*,
     *   CreationDateTime?: null|\DateTimeImmutable,
     *   ProvisionedThroughput?: null|\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputDescription|array,
     *   TableSizeBytes?: null|string,
     *   ItemCount?: null|string,
     *   TableArn?: null|string,
     *   TableId?: null|string,
     *   BillingModeSummary?: null|\AsyncAws\DynamoDb\ValueObject\BillingModeSummary|array,
     *   LocalSecondaryIndexes?: null|\AsyncAws\DynamoDb\ValueObject\LocalSecondaryIndexDescription[],
     *   GlobalSecondaryIndexes?: null|\AsyncAws\DynamoDb\ValueObject\GlobalSecondaryIndexDescription[],
     *   StreamSpecification?: null|\AsyncAws\DynamoDb\ValueObject\StreamSpecification|array,
     *   LatestStreamLabel?: null|string,
     *   LatestStreamArn?: null|string,
     *   GlobalTableVersion?: null|string,
     *   Replicas?: null|\AsyncAws\DynamoDb\ValueObject\ReplicaDescription[],
     *   RestoreSummary?: null|\AsyncAws\DynamoDb\ValueObject\RestoreSummary|array,
     *   SSEDescription?: null|\AsyncAws\DynamoDb\ValueObject\SSEDescription|array,
     *   ArchivalSummary?: null|\AsyncAws\DynamoDb\ValueObject\ArchivalSummary|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->AttributeDefinitions = isset($input['AttributeDefinitions']) ? array_map([AttributeDefinition::class, 'create'], $input['AttributeDefinitions']) : null;
        $this->TableName = $input['TableName'] ?? null;
        $this->KeySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->TableStatus = $input['TableStatus'] ?? null;
        $this->CreationDateTime = $input['CreationDateTime'] ?? null;
        $this->ProvisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughputDescription::create($input['ProvisionedThroughput']) : null;
        $this->TableSizeBytes = $input['TableSizeBytes'] ?? null;
        $this->ItemCount = $input['ItemCount'] ?? null;
        $this->TableArn = $input['TableArn'] ?? null;
        $this->TableId = $input['TableId'] ?? null;
        $this->BillingModeSummary = isset($input['BillingModeSummary']) ? BillingModeSummary::create($input['BillingModeSummary']) : null;
        $this->LocalSecondaryIndexes = isset($input['LocalSecondaryIndexes']) ? array_map([LocalSecondaryIndexDescription::class, 'create'], $input['LocalSecondaryIndexes']) : null;
        $this->GlobalSecondaryIndexes = isset($input['GlobalSecondaryIndexes']) ? array_map([GlobalSecondaryIndexDescription::class, 'create'], $input['GlobalSecondaryIndexes']) : null;
        $this->StreamSpecification = isset($input['StreamSpecification']) ? StreamSpecification::create($input['StreamSpecification']) : null;
        $this->LatestStreamLabel = $input['LatestStreamLabel'] ?? null;
        $this->LatestStreamArn = $input['LatestStreamArn'] ?? null;
        $this->GlobalTableVersion = $input['GlobalTableVersion'] ?? null;
        $this->Replicas = isset($input['Replicas']) ? array_map([ReplicaDescription::class, 'create'], $input['Replicas']) : null;
        $this->RestoreSummary = isset($input['RestoreSummary']) ? RestoreSummary::create($input['RestoreSummary']) : null;
        $this->SSEDescription = isset($input['SSEDescription']) ? SSEDescription::create($input['SSEDescription']) : null;
        $this->ArchivalSummary = isset($input['ArchivalSummary']) ? ArchivalSummary::create($input['ArchivalSummary']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getArchivalSummary(): ?ArchivalSummary
    {
        return $this->ArchivalSummary;
    }

    /**
     * @return AttributeDefinition[]
     */
    public function getAttributeDefinitions(): array
    {
        return $this->AttributeDefinitions ?? [];
    }

    public function getBillingModeSummary(): ?BillingModeSummary
    {
        return $this->BillingModeSummary;
    }

    public function getCreationDateTime(): ?\DateTimeImmutable
    {
        return $this->CreationDateTime;
    }

    /**
     * @return GlobalSecondaryIndexDescription[]
     */
    public function getGlobalSecondaryIndexes(): array
    {
        return $this->GlobalSecondaryIndexes ?? [];
    }

    public function getGlobalTableVersion(): ?string
    {
        return $this->GlobalTableVersion;
    }

    public function getItemCount(): ?string
    {
        return $this->ItemCount;
    }

    /**
     * @return KeySchemaElement[]
     */
    public function getKeySchema(): array
    {
        return $this->KeySchema ?? [];
    }

    public function getLatestStreamArn(): ?string
    {
        return $this->LatestStreamArn;
    }

    public function getLatestStreamLabel(): ?string
    {
        return $this->LatestStreamLabel;
    }

    /**
     * @return LocalSecondaryIndexDescription[]
     */
    public function getLocalSecondaryIndexes(): array
    {
        return $this->LocalSecondaryIndexes ?? [];
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughputDescription
    {
        return $this->ProvisionedThroughput;
    }

    /**
     * @return ReplicaDescription[]
     */
    public function getReplicas(): array
    {
        return $this->Replicas ?? [];
    }

    public function getRestoreSummary(): ?RestoreSummary
    {
        return $this->RestoreSummary;
    }

    public function getSSEDescription(): ?SSEDescription
    {
        return $this->SSEDescription;
    }

    public function getStreamSpecification(): ?StreamSpecification
    {
        return $this->StreamSpecification;
    }

    public function getTableArn(): ?string
    {
        return $this->TableArn;
    }

    public function getTableId(): ?string
    {
        return $this->TableId;
    }

    public function getTableName(): ?string
    {
        return $this->TableName;
    }

    public function getTableSizeBytes(): ?string
    {
        return $this->TableSizeBytes;
    }

    /**
     * @return TableStatus::*|null
     */
    public function getTableStatus(): ?string
    {
        return $this->TableStatus;
    }
}
