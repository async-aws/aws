<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\DynamoDb\Enum\IndexStatus;
use AsyncAws\DynamoDb\Enum\VectorDistanceFunction;

/**
 * Contains the current state and configuration of a vector index, including its status, size, item count, and the
 * settings specified when the index was created.
 */
final class VectorIndexDescription
{
    /**
     * The name of the vector index.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The search schema that defines partition key and inline filter attributes for the vector index.
     *
     * @var SearchSchemaElement[]|null
     */
    private $searchSchema;

    /**
     * Specifies attributes that are copied (projected) from the table into the vector index.
     *
     * @var Projection|null
     */
    private $projection;

    /**
     * The vector attribute configuration for the index.
     *
     * @var VectorAttributeDefinition|null
     */
    private $vectorAttribute;

    /**
     * The number of dimensions in each vector.
     *
     * @var int|null
     */
    private $dimensions;

    /**
     * The distance function used to calculate similarity between vectors.
     *
     * @var VectorDistanceFunction::*|null
     */
    private $distanceFunction;

    /**
     * The current state of the vector index:
     *
     * - `CREATING` - The index is being created.
     * - `ACTIVE` - The index is ready for use.
     * - `DELETING` - The index is being deleted.
     *
     * @var IndexStatus::*|null
     */
    private $indexStatus;

    /**
     * Specifies whether the index is currently backfilling. During backfill, `SearchVectors` operations might return
     * incomplete results.
     *
     * @var bool|null
     */
    private $backfilling;

    /**
     * The total size of the vector index, in bytes. Amazon DynamoDB updates this value approximately every six hours.
     * Recent changes might not be reflected in this value.
     *
     * @var int|null
     */
    private $indexSizeBytes;

    /**
     * The number of items indexed in the vector index. Amazon DynamoDB updates this value approximately every six hours.
     * Recent changes might not be reflected in this value.
     *
     * @var int|null
     */
    private $itemCount;

    /**
     * The Amazon Resource Name (ARN) that uniquely identifies the vector index.
     *
     * @var string|null
     */
    private $indexArn;

    /**
     * @param array{
     *   IndexName?: string|null,
     *   SearchSchema?: array<SearchSchemaElement|array>|null,
     *   Projection?: Projection|array|null,
     *   VectorAttribute?: VectorAttributeDefinition|array|null,
     *   Dimensions?: int|null,
     *   DistanceFunction?: VectorDistanceFunction::*|null,
     *   IndexStatus?: IndexStatus::*|null,
     *   Backfilling?: bool|null,
     *   IndexSizeBytes?: int|null,
     *   ItemCount?: int|null,
     *   IndexArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->searchSchema = isset($input['SearchSchema']) ? array_map([SearchSchemaElement::class, 'create'], $input['SearchSchema']) : null;
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
        $this->vectorAttribute = isset($input['VectorAttribute']) ? VectorAttributeDefinition::create($input['VectorAttribute']) : null;
        $this->dimensions = $input['Dimensions'] ?? null;
        $this->distanceFunction = $input['DistanceFunction'] ?? null;
        $this->indexStatus = $input['IndexStatus'] ?? null;
        $this->backfilling = $input['Backfilling'] ?? null;
        $this->indexSizeBytes = $input['IndexSizeBytes'] ?? null;
        $this->itemCount = $input['ItemCount'] ?? null;
        $this->indexArn = $input['IndexArn'] ?? null;
    }

    /**
     * @param array{
     *   IndexName?: string|null,
     *   SearchSchema?: array<SearchSchemaElement|array>|null,
     *   Projection?: Projection|array|null,
     *   VectorAttribute?: VectorAttributeDefinition|array|null,
     *   Dimensions?: int|null,
     *   DistanceFunction?: VectorDistanceFunction::*|null,
     *   IndexStatus?: IndexStatus::*|null,
     *   Backfilling?: bool|null,
     *   IndexSizeBytes?: int|null,
     *   ItemCount?: int|null,
     *   IndexArn?: string|null,
     * }|VectorIndexDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBackfilling(): ?bool
    {
        return $this->backfilling;
    }

    public function getDimensions(): ?int
    {
        return $this->dimensions;
    }

    /**
     * @return VectorDistanceFunction::*|null
     */
    public function getDistanceFunction(): ?string
    {
        return $this->distanceFunction;
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

    public function getProjection(): ?Projection
    {
        return $this->projection;
    }

    /**
     * @return SearchSchemaElement[]
     */
    public function getSearchSchema(): array
    {
        return $this->searchSchema ?? [];
    }

    public function getVectorAttribute(): ?VectorAttributeDefinition
    {
        return $this->vectorAttribute;
    }
}
