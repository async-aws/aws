<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The parameters required for creating a global secondary index on an existing table:.
 *
 * - `IndexName `
 * - `KeySchema `
 * - `AttributeDefinitions `
 * - `Projection `
 * - `ProvisionedThroughput `
 */
final class CreateGlobalSecondaryIndexAction
{
    /**
     * The name of the global secondary index to be created.
     */
    private $indexName;

    /**
     * The key schema for the global secondary index.
     */
    private $keySchema;

    /**
     * Represents attributes that are copied (projected) from the table into an index. These are in addition to the primary
     * key attributes and index key attributes, which are automatically projected.
     */
    private $projection;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     */
    private $provisionedThroughput;

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: KeySchemaElement[],
     *   Projection: Projection|array,
     *   ProvisionedThroughput?: null|ProvisionedThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->indexName = $input['IndexName'] ?? null;
        $this->keySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
        $this->provisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
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
        if (null !== $v = $this->provisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }

        return $payload;
    }
}
