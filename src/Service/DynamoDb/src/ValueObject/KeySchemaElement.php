<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\KeyType;

/**
 * Represents *a single element* of a key schema. A key schema specifies the attributes that make up the primary key of
 * a table, or the key attributes of an index.
 *
 * A `KeySchemaElement` represents exactly one attribute of the primary key. For example, a simple primary key would be
 * represented by one `KeySchemaElement` (for the partition key). A composite primary key would require one
 * `KeySchemaElement` for the partition key, and another `KeySchemaElement` for the sort key.
 *
 * A `KeySchemaElement` must be a scalar, top-level attribute (not a nested attribute). The data type must be one of
 * String, Number, or Binary. The attribute cannot be nested within a List or a Map.
 */
final class KeySchemaElement
{
    /**
     * The name of a key attribute.
     *
     * @var string
     */
    private $attributeName;

    /**
     * The role that this key attribute will assume:
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
     * @var KeyType::*
     */
    private $keyType;

    /**
     * @param array{
     *   AttributeName: string,
     *   KeyType: KeyType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attributeName = $input['AttributeName'] ?? $this->throwException(new InvalidArgument('Missing required field "AttributeName".'));
        $this->keyType = $input['KeyType'] ?? $this->throwException(new InvalidArgument('Missing required field "KeyType".'));
    }

    /**
     * @param array{
     *   AttributeName: string,
     *   KeyType: KeyType::*,
     * }|KeySchemaElement $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    /**
     * @return KeyType::*
     */
    public function getKeyType(): string
    {
        return $this->keyType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->attributeName;
        $payload['AttributeName'] = $v;
        $v = $this->keyType;
        if (!KeyType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "KeyType" for "%s". The value "%s" is not a valid "KeyType".', __CLASS__, $v));
        }
        $payload['KeyType'] = $v;

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
