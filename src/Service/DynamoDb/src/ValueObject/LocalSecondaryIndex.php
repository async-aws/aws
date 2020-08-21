<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class LocalSecondaryIndex
{
    /**
     * The name of the local secondary index. The name must be unique among all other indexes on this table.
     */
    private $IndexName;

    /**
     * The complete key schema for the local secondary index, consisting of one or more pairs of attribute names and key
     * types:.
     */
    private $KeySchema;

    /**
     * Represents attributes that are copied (projected) from the table into the local secondary index. These are in
     * addition to the primary key attributes and index key attributes, which are automatically projected.
     */
    private $Projection;

    /**
     * @param array{
     *   IndexName: string,
     *   KeySchema: KeySchemaElement[],
     *   Projection: Projection|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->IndexName = $input['IndexName'] ?? null;
        $this->KeySchema = isset($input['KeySchema']) ? array_map([KeySchemaElement::class, 'create'], $input['KeySchema']) : null;
        $this->Projection = isset($input['Projection']) ? Projection::create($input['Projection']) : null;
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

        return $payload;
    }
}
