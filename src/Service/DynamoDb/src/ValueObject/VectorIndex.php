<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\VectorDistanceFunction;

/**
 * Contains the configuration settings for a vector index, including the index name, vector attribute, dimensions,
 * distance function, search schema, and projection.
 */
final class VectorIndex
{
    /**
     * The name of the vector index.
     *
     * @var string
     */
    private $indexName;

    /**
     * The vector attribute configuration for the index.
     *
     * @var VectorAttributeDefinition
     */
    private $vectorAttribute;

    /**
     * The search schema that defines partition key and inline filter attributes for the vector index.
     *
     * @var SearchSchemaElement[]|null
     */
    private $searchSchema;

    /**
     * Specifies attributes that are copied (projected) from the table into the vector index.
     *
     * @var Projection
     */
    private $projection;

    /**
     * The number of dimensions in each vector.
     *
     * @var int
     */
    private $dimensions;

    /**
     * The distance function used to calculate similarity between vectors. Valid values: `COSINE`, `EUCLIDEAN`,
     * `DOT_PRODUCT`.
     *
     * @var VectorDistanceFunction::*
     */
    private $distanceFunction;

    /**
     * @param array{
     *   IndexName: string,
     *   VectorAttribute: VectorAttributeDefinition|array,
     *   SearchSchema?: array<SearchSchemaElement|array>|null,
     *   Projection: Projection|array,
     *   Dimensions: int,
     *   DistanceFunction: VectorDistanceFunction::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? $this->throwException(new InvalidArgument('Missing required field "IndexName".'));
        $this->vectorAttribute = isset($input['VectorAttribute']) ? VectorAttributeDefinition::create($input['VectorAttribute']) : $this->throwException(new InvalidArgument('Missing required field "VectorAttribute".'));
        $this->searchSchema = isset($input['SearchSchema']) ? array_map([SearchSchemaElement::class, 'create'], $input['SearchSchema']) : null;
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : $this->throwException(new InvalidArgument('Missing required field "Projection".'));
        $this->dimensions = $input['Dimensions'] ?? $this->throwException(new InvalidArgument('Missing required field "Dimensions".'));
        $this->distanceFunction = $input['DistanceFunction'] ?? $this->throwException(new InvalidArgument('Missing required field "DistanceFunction".'));
    }

    /**
     * @param array{
     *   IndexName: string,
     *   VectorAttribute: VectorAttributeDefinition|array,
     *   SearchSchema?: array<SearchSchemaElement|array>|null,
     *   Projection: Projection|array,
     *   Dimensions: int,
     *   DistanceFunction: VectorDistanceFunction::*,
     * }|VectorIndex $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDimensions(): int
    {
        return $this->dimensions;
    }

    /**
     * @return VectorDistanceFunction::*
     */
    public function getDistanceFunction(): string
    {
        return $this->distanceFunction;
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    public function getProjection(): Projection
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

    public function getVectorAttribute(): VectorAttributeDefinition
    {
        return $this->vectorAttribute;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->indexName;
        $payload['IndexName'] = $v;
        $v = $this->vectorAttribute;
        $payload['VectorAttribute'] = $v->requestBody();
        if (null !== $v = $this->searchSchema) {
            $index = -1;
            $payload['SearchSchema'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['SearchSchema'][$index] = $listValue->requestBody();
            }
        }
        $v = $this->projection;
        $payload['Projection'] = $v->requestBody();
        $v = $this->dimensions;
        $payload['Dimensions'] = $v;
        $v = $this->distanceFunction;
        if (!VectorDistanceFunction::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "DistanceFunction" for "%s". The value "%s" is not a valid "VectorDistanceFunction".', __CLASS__, $v));
        }
        $payload['DistanceFunction'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
