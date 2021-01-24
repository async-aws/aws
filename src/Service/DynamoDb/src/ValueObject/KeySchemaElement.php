<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\KeyType;

/**
 * Represents *a single element* of a key schema. A key schema specifies the attributes that make up the primary key of
 * a table, or the key attributes of an index.
 * A `KeySchemaElement` represents exactly one attribute of the primary key. For example, a simple primary key would be
 * represented by one `KeySchemaElement` (for the partition key). A composite primary key would require one
 * `KeySchemaElement` for the partition key, and another `KeySchemaElement` for the sort key.
 * A `KeySchemaElement` must be a scalar, top-level attribute (not a nested attribute). The data type must be one of
 * String, Number, or Binary. The attribute cannot be nested within a List or a Map.
 */
final class KeySchemaElement
{
    /**
     * The name of a key attribute.
     */
    private $AttributeName;

    /**
     * The role that this key attribute will assume:.
     */
    private $KeyType;

    /**
     * @param array{
     *   AttributeName: string,
     *   KeyType: KeyType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->AttributeName = $input['AttributeName'] ?? null;
        $this->KeyType = $input['KeyType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): string
    {
        return $this->AttributeName;
    }

    /**
     * @return KeyType::*
     */
    public function getKeyType(): string
    {
        return $this->KeyType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->AttributeName) {
            throw new InvalidArgument(sprintf('Missing parameter "AttributeName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AttributeName'] = $v;
        if (null === $v = $this->KeyType) {
            throw new InvalidArgument(sprintf('Missing parameter "KeyType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!KeyType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "KeyType" for "%s". The value "%s" is not a valid "KeyType".', __CLASS__, $v));
        }
        $payload['KeyType'] = $v;

        return $payload;
    }
}
