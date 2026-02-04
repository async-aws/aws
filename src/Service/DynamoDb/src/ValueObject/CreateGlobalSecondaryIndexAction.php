<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a new global secondary index to be added to an existing table.
 */
final class CreateGlobalSecondaryIndexAction
{
    /**
     * The name of the global secondary index to be created.
     *
     * @var string
     */
    private $indexName;

    /**
     * The key schema for the global secondary index. Global secondary index supports up to 4 partition and up to 4 sort
     * keys.
     *
     * @var KeySchemaElement[]
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into an index. These are in addition to the primary
     * key attributes and index key attributes, which are automatically projected.
     *
     * @var Projection
     */
    private $projection;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
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
     * The maximum number of read and write units for the global secondary index being created. If you use this parameter,
     * you must specify `MaxReadRequestUnits`, `MaxWriteRequestUnits`, or both. You must use either `OnDemand Throughput` or
     * `ProvisionedThroughput` based on your table's capacity mode.
     *
     * @var OnDemandThroughput|null
     */
    private $onDemandThroughput;

    /**
     * Represents the warm throughput value (in read units per second and write units per second) when creating a secondary
     * index.
     *
     * @var WarmThroughput|null
     */
    private $warmThroughput;

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: array<KeySchemaElement|array>,
     *   Projection: Projection|array,
     *   ProvisionedThroughput?: ProvisionedThroughput|array|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   WarmThroughput?: WarmThroughput|array|null,
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
     *   ProvisionedThroughput?: ProvisionedThroughput|array|null,
     *   OnDemandThroughput?: OnDemandThroughput|array|null,
     *   WarmThroughput?: WarmThroughput|array|null,
     * }|CreateGlobalSecondaryIndexAction $input
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
