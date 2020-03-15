<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\IndexStatus;

class GlobalSecondaryIndexDescription
{
    /**
     * The name of the global secondary index.
     */
    private $IndexName;

    /**
     * The complete key schema for a global secondary index, which consists of one or more pairs of attribute names and key
     * types:.
     */
    private $KeySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the global secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     */
    private $Projection;

    /**
     * The current state of the global secondary index:.
     */
    private $IndexStatus;

    /**
     * Indicates whether the index is currently backfilling. *Backfilling* is the process of reading items from the table
     * and determining whether they can be added to the index. (Not all items will qualify: For example, a partition key
     * cannot have any duplicate values.) If an item can be added to the index, DynamoDB will do so. After all items have
     * been processed, the backfilling operation is complete and `Backfilling` is false.
     */
    private $Backfilling;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     */
    private $ProvisionedThroughput;

    /**
     * The total size of the specified index, in bytes. DynamoDB updates this value approximately every six hours. Recent
     * changes might not be reflected in this value.
     */
    private $IndexSizeBytes;

    /**
     * The number of items in the specified index. DynamoDB updates this value approximately every six hours. Recent changes
     * might not be reflected in this value.
     */
    private $ItemCount;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the index.
     */
    private $IndexArn;

    /**
     * @param array{
     *   IndexName?: null|string,
     *   KeySchema?: null|\AsyncAws\DynamoDb\ValueObject\KeySchemaElement[],
     *   Projection?: null|\AsyncAws\DynamoDb\ValueObject\Projection|array,
     *   IndexStatus?: null|\AsyncAws\DynamoDb\Enum\IndexStatus::*,
     *   Backfilling?: null|bool,
     *   ProvisionedThroughput?: null|\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughputDescription|array,
     *   IndexSizeBytes?: null|string,
     *   ItemCount?: null|string,
     *   IndexArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->IndexName = $input['IndexName'] ?? null;
        $this->KeySchema = array_map([KeySchemaElement::class, 'create'], $input['KeySchema'] ?? []);
        $this->Projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
        $this->IndexStatus = $input['IndexStatus'] ?? null;
        $this->Backfilling = $input['Backfilling'] ?? null;
        $this->ProvisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughputDescription::create($input['ProvisionedThroughput']) : null;
        $this->IndexSizeBytes = $input['IndexSizeBytes'] ?? null;
        $this->ItemCount = $input['ItemCount'] ?? null;
        $this->IndexArn = $input['IndexArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBackfilling(): ?bool
    {
        return $this->Backfilling;
    }

    public function getIndexArn(): ?string
    {
        return $this->IndexArn;
    }

    public function getIndexName(): ?string
    {
        return $this->IndexName;
    }

    public function getIndexSizeBytes(): ?string
    {
        return $this->IndexSizeBytes;
    }

    /**
     * @return IndexStatus::*|null
     */
    public function getIndexStatus(): ?string
    {
        return $this->IndexStatus;
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
        return $this->KeySchema;
    }

    public function getProjection(): ?Projection
    {
        return $this->Projection;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughputDescription
    {
        return $this->ProvisionedThroughput;
    }
}
