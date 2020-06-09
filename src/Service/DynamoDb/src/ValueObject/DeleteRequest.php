<?php

namespace AsyncAws\DynamoDb\ValueObject;

final class DeleteRequest
{
    /**
     * A map of attribute name to attribute values, representing the primary key of the item to delete. All of the table's
     * primary key attributes must be specified, and their data types must match those of the table's key schema.
     */
    private $Key;

    /**
     * @param array{
     *   Key: array<string, \AsyncAws\DynamoDb\ValueObject\AttributeValue>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = array_map([AttributeValue::class, 'create'], $input['Key'] ?? []);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getKey(): array
    {
        return $this->Key;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];

        foreach ($this->Key as $name => $v) {
            $payload['Key'][$name] = $v->requestBody();
        }

        return $payload;
    }
}
