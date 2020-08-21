<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class LocalSecondaryIndexDescription
{
    /**
     * Represents the name of the local secondary index.
     */
    private $IndexName;

    /**
     * The complete key schema for the local secondary index, consisting of one or more pairs of attribute names and key
     * types:.
     */
    private $KeySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the global secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     */
    private $Projection;

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
     *   KeySchema?: null|KeySchemaElement[],
     *   Projection?: null|Projection|array,
     *   IndexSizeBytes?: null|string,
     *   ItemCount?: null|string,
     *   IndexArn?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->IndexName = $input['IndexName'] ?? null;
        $this->KeySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->Projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
        $this->IndexSizeBytes = $input['IndexSizeBytes'] ?? null;
        $this->ItemCount = $input['ItemCount'] ?? null;
        $this->IndexArn = $input['IndexArn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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

    public function getProjection(): ?Projection
    {
        return $this->Projection;
    }
}
