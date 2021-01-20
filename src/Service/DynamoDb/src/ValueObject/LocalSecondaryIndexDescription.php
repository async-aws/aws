<?php

namespace AsyncAws\DynamoDb\ValueObject;

/**
 * Represents the properties of a local secondary index.
 */
final class LocalSecondaryIndexDescription
{
    /**
     * Represents the name of the local secondary index.
     */
    private $indexName;

    /**
     * The complete key schema for the local secondary index, consisting of one or more pairs of attribute names and key
     * types:.
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the global secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     */
    private $projection;

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
        $this->indexSizeBytes = $input['IndexSizeBytes'] ?? null;
        $this->itemCount = $input['ItemCount'] ?? null;
        $this->indexArn = $input['IndexArn'] ?? null;
    }

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

    public function getIndexSizeBytes(): ?string
    {
        return $this->indexSizeBytes;
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
}
