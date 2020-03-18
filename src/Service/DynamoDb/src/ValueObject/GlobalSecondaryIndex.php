<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class GlobalSecondaryIndex
{
    /**
     * The name of the global secondary index. The name must be unique among all other indexes on this table.
     */
    private $IndexName;

    /**
     * The complete key schema for a global secondary index, which consists of one or more pairs of attribute names and key
     * types:.
     */
    private $KeySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the global secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     */
    private $Projection;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     */
    private $ProvisionedThroughput;

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: \AsyncAws\DynamoDb\ValueObject\KeySchemaElement[],
     *   Projection: \AsyncAws\DynamoDb\ValueObject\Projection|array,
     *   ProvisionedThroughput?: null|\AsyncAws\DynamoDb\ValueObject\ProvisionedThroughput|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->IndexName = $input['IndexName'] ?? null;
        $this->KeySchema = array_map([KeySchemaElement::class, 'create'], $input['KeySchema'] ?? []);
        $this->Projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
        $this->ProvisionedThroughput = isset($input['ProvisionedThroughput']) ? ProvisionedThroughput::create($input['ProvisionedThroughput']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIndexName(): string
    {
        return $this->IndexName;
    }

    /**
     * @return KeySchemaElement[]
     */
    public function getKeySchema(): array
    {
        return $this->KeySchema;
    }

    public function getProjection(): Projection
    {
        return $this->Projection;
    }

    public function getProvisionedThroughput(): ?ProvisionedThroughput
    {
        return $this->ProvisionedThroughput;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->IndexName) {
            throw new InvalidArgument(sprintf('Missing parameter "IndexName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['IndexName'] = $v;

        $index = -1;
        foreach ($this->KeySchema as $listValue) {
            ++$index;
            $payload['KeySchema'][$index] = $listValue->requestBody();
        }

        if (null === $v = $this->Projection) {
            throw new InvalidArgument(sprintf('Missing parameter "Projection" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Projection'] = $v->requestBody();
        if (null !== $v = $this->ProvisionedThroughput) {
            $payload['ProvisionedThroughput'] = $v->requestBody();
        }

        return $payload;
    }
}
