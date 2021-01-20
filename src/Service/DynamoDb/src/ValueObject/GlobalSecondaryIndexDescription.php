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
     */
    private $indexName;

    /**
     * The complete key schema for a global secondary index, which consists of one or more pairs of attribute names and key
     * types:.
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the global secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     */
    private $projection;

    /**
     * The current state of the global secondary index:.
     */
    private $indexStatus;

    /**
     * Indicates whether the index is currently backfilling. *Backfilling* is the process of reading items from the table
     * and determining whether they can be added to the index. (Not all items will qualify: For example, a partition key
     * cannot have any duplicate values.) If an item can be added to the index, DynamoDB will do so. After all items have
     * been processed, the backfilling operation is complete and `Backfilling` is false.
     */
    private $backfilling;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     */
    private $provisionedThroughput;

    /**
     * The total size of the specified index, in bytes. DynamoDB updates this value approximately every six hours. Recent
     * changes might not be reflected in this value.
     */
    private $indexSizeBytes;

    /**
     * The number of items in the specified index. DynamoDB updates this value approximately every six hours. Recent changes
     * might not be reflected in this value.
     */
    private $itemCount;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the index.
     */
    private $indexArn;

    /**
     * @param array{
     *   IndexName?: null|string,
     *   KeySchema?: null|KeySchemaElement[],
     *   Projection?: null|Projection|array,
     *   IndexStatus?: null|IndexStatus::*,
     *   Backfilling?: null|bool,
     *   ProvisionedThroughput?: null|ProvisionedThroughputDescription|array,
     *   IndexSizeBytes?: null|string,
     *   ItemCount?: null|string,
     *   IndexArn?: null|string,
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
    }

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

    public function getIndexSizeBytes(): ?string
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

    public function getProjection(): ?Projection
    {
        return $this->projection;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughputDescription
    {
        return $this->provisionedThroughput;
    }
}
