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
     *
     * @var string
     */
    private $indexName;

    /**
     * The complete key schema for the local secondary index, consisting of one or more pairs of attribute names and key
     * types:.
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
     * @var KeySchemaElement[]
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the local secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     *
     * @var Projection
     */
    private $projection;

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: array<KeySchemaElement|array>,
     *   Projection: Projection|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? $this->throwException(new InvalidArgument('Missing required field "IndexName".'));
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : $this->throwException(new InvalidArgument('Missing required field "KeySchema".'));
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : $this->throwException(new InvalidArgument('Missing required field "Projection".'));
    }

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: array<KeySchemaElement|array>,
     *   Projection: Projection|array,
     * }|LocalSecondaryIndex $input
     */
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
        return $this->keySchema;
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
        $v = $this->indexName;
        $payload['IndexName'] = $v;
        $v = $this->keySchema;

        $index = -1;
        $payload['KeySchema'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['KeySchema'][$index] = $listValue->requestBody();
        }

        $v = $this->projection;
        $payload['Projection'] = $v->requestBody();

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
