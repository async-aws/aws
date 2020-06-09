<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class PutRequest
{
    /**
     * A map of attribute name to attribute values, representing the primary key of an item to be processed by `PutItem`.
     * All of the table's primary key attributes must be specified, and their data types must match those of the table's key
     * schema. If any attributes are present in the item that are part of an index key schema for the table, their types
     * must match the index key schema.
     */
    private $Item;

    /**
     * @param array{
     *   Item: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Item = array_map([AttributeValue::class, 'create'], $input['Item'] ?? []);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getItem(): array
    {
        return $this->Item;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];

        foreach ($this->Item as $name => $v) {
            $payload['Item'][$name] = $v->requestBody();
        }

        return $payload;
    }
}
