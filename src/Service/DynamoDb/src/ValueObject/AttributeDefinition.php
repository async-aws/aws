<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ScalarAttributeType;

/**
 * Represents an attribute for describing the key schema for the table and indexes.
 */
final class AttributeDefinition
{
    /**
     * A name for the attribute.
     */
    private $attributeName;

    /**
     * The data type for the attribute, where:.
     */
    private $attributeType;

    /**
     * @param array{
     *   AttributeName: string,
     *   AttributeType: ScalarAttributeType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attributeName = $input['AttributeName'] ?? null;
        $this->attributeType = $input['AttributeType'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    /**
     * @return ScalarAttributeType::*
     */
    public function getAttributeType(): string
    {
        return $this->attributeType;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->attributeName) {
            throw new InvalidArgument(sprintf('Missing parameter "AttributeName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['AttributeName'] = $v;
        if (null === $v = $this->attributeType) {
            throw new InvalidArgument(sprintf('Missing parameter "AttributeType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ScalarAttributeType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "AttributeType" for "%s". The value "%s" is not a valid "ScalarAttributeType".', __CLASS__, $v));
        }
        $payload['AttributeType'] = $v;

        return $payload;
    }
}
