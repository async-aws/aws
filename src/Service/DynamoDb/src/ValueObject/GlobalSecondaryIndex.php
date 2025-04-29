<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the properties of a global secondary index.
 */
final class GlobalSecondaryIndex
{
    /**
     * The name of the global secondary index. The name must be unique among all other indexes on this table.
     *
     * @var string
     */
    private $indexName;

    /**
     * The complete key schema for a global secondary index, which consists of one or more pairs of attribute names and key
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
     * @var KeySchemaElement[]
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the global secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     *
     * @var Projection
     */
    private $projection;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index. You must use either
     * `OnDemandThroughput` or `ProvisionedThroughput` based on your table's capacity mode.
     *
     * For current minimum and maximum provisioned throughput values, see Service, Account, and Table Quotas [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
     *
     * @var ProvisionedThroughput|null
     */
    private $provisionedThroughput;

    /**
     * The maximum number of read and write units for the specified global secondary index. If you use this parameter, you
     * must specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both. You must use either `OnDemandThroughput` or
     * `ProvisionedThroughput` based on your table's capacity mode.
     *
     * @var OnDemandThroughput|null
     */
    private $onDemandThroughput;

    /**
     * Represents the warm throughput value (in read units per second and write units per second) for the specified
     * secondary index. If you use this parameter, you must specify `ReadUnitsPerSecond`, `WriteUnitsPerSecond`, or both.
     *
     * @var WarmThroughput|null
     */
    private $warmThroughput;

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: array<KeySchemaElement|array>,
     *   Projection: Projection|array,
     *   ProvisionedThroughput?: null|ProvisionedThroughput|array,
     *   OnDemandThroughput?: null|OnDemandThroughput|array,
     *   WarmThroughput?: null|WarmThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? $this->throwException(new InvalidArgument('Missing required field "IndexName".'));
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : $this->throwException(new InvalidArgument('Missing required field "KeySchema".'));
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : $this->throwException(new InvalidArgument('Missing required field "Projection".'));
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
        $this->onDemandThroughput = isset($input['OnDemandThroughput']) ? OnDemandThroughput::create($input['OnDemandThroughput']) : null;
        $this->warmThroughput = isset($input['WarmThroughput']) ? WarmThroughput::create($input['WarmThroughput']) : null;
    }

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: array<KeySchemaElement|array>,
     *   Projection: Projection|array,
     *   ProvisionedThroughput?: null|ProvisionedThroughput|array,
     *   OnDemandThroughput?: null|OnDemandThroughput|array,
     *   WarmThroughput?: null|WarmThroughput|array,
     * }|GlobalSecondaryIndex $input
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

    public function getOnDemandThroughput(): ?OnDemandThroughput
    {
        return $this->onDemandThroughput;
    }

    public function getProjection(): Projection
    {
        return $this->projection;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->provisionedThroughput;
    }

    public function getWarmThroughput(): ?WarmThroughput
    {
        return $this->warmThroughput;
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
        if (null !== $v = $this->provisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->onDemandThroughput) {
            $payload['OnDemandThroughput'] = $v->requestBody();
        }
        if (null !== $v = $this->warmThroughput) {
            $payload['WarmThroughput'] = $v->requestBody();
        }

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
