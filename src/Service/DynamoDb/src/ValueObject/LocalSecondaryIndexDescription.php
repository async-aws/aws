<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the properties of a local secondary index.
 */
final class LocalSecondaryIndexDescription
{
    /**
     * Represents the name of the local secondary index.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The complete key schema for the local secondary index, consisting of one or more pairs of attribute names and key
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
     * @param array{
     *   IndexName?: string|null,
     *   KeySchema?: array<KeySchemaElement|array>|null,
     *   Projection?: Projection|array|null,
     *   IndexSizeBytes?: int|null,
     *   ItemCount?: int|null,
     *   IndexArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
        $this->indexSizeBytes = $input['IndexSizeBytes'] ?? null;
        $this->itemCount = $input['ItemCount'] ?? null;
        $this->indexArn = $input['IndexArn'] ?? null;
    }

    /**
     * @param array{
     *   IndexName?: string|null,
     *   KeySchema?: array<KeySchemaElement|array>|null,
     *   Projection?: Projection|array|null,
     *   IndexSizeBytes?: int|null,
     *   ItemCount?: int|null,
     *   IndexArn?: string|null,
     * }|LocalSecondaryIndexDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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

    public function getProjection(): ?Projection
    {
        return $this->projection;
    }
}
