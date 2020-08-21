<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class CreateGlobalSecondaryIndexAction
{
    /**
     * The name of the global secondary index to be created.
     */
    private $IndexName;

    /**
     * The key schema for the global secondary index.
     */
    private $KeySchema;

    /**
     * Represents attributes that are copied (projected) from the table into an index. These are in addition to the primary
     * key attributes and index key attributes, which are automatically projected.
     */
    private $Projection;

    /**
     * Represents the provisioned throughput settings for the specified global secondary index.
     */
    private $ProvisionedThroughput;

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
        $this->IndexName = $input['IndexName'] ?? null;
        $this->KeySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
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
        return $this->KeySchema ?? [];
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
        if (null === $v = $this->KeySchema) {
            throw new InvalidArgument(sprintf('Missing parameter "KeySchema" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['KeySchema'] = [];
        foreach ($v as $listValue) {
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
