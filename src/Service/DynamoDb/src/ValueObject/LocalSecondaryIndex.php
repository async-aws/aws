<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the properties of a local secondary index.
 */
final class LocalSecondaryIndex
{
    /**
     * The name of the local secondary index. The name must be unique among all other indexes on this table.
     */
    private $indexName;

    /**
     * The complete key schema for the local secondary index, consisting of one or more pairs of attribute names and key
     * types:.
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the local secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     */
    private $projection;

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: KeySchemaElement[],
     *   Projection: Projection|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): string
    {
        return $this->indexName;
    }

    /**
     * @return KeySchemaElement[]
     */
    public function getKeySchema(): array
    {
        return $this->keySchema ?? [];
    }

    public function getProjection(): Projection
    {
        return $this->projection;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->indexName) {
            throw new InvalidArgument(sprintf('Missing parameter "IndexName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['IndexName'] = $v;
        if (null === $v = $this->keySchema) {
            throw new InvalidArgument(sprintf('Missing parameter "KeySchema" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['KeySchema'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['KeySchema'][$index] = $listValue->requestBody();
        }

        if (null === $v = $this->projection) {
            throw new InvalidArgument(sprintf('Missing parameter "Projection" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Projection'] = $v->requestBody();

        return $payload;
    }
}
