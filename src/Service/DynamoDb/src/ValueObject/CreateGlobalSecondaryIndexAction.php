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
     * The key schema for the global secondary index.
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
     * @param array{
     *   IndexName: string,
     *   KeySchema: array<KeySchemaElement|array>,
     *   Projection: Projection|array,
     *   ProvisionedThroughput?: null|ProvisionedThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? $this->throwException(new InvalidArgument('Missing required field "IndexName".'));
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : $this->throwException(new InvalidArgument('Missing required field "KeySchema".'));
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : $this->throwException(new InvalidArgument('Missing required field "Projection".'));
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
    }

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: array<KeySchemaElement|array>,
     *   Projection: Projection|array,
     *   ProvisionedThroughput?: null|ProvisionedThroughput|array,
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

    public function getProjection(): Projection
    {
        return $this->projection;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->provisionedThroughput;
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
