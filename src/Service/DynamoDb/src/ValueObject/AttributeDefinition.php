<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ScalarAttributeType;

/**
 * Represents an attribute for describing the schema for the table and indexes.
 */
final class AttributeDefinition
{
    /**
     * A name for the attribute.
     *
     * @var string
     */
    private $attributeName;

    /**
     * The data type for the attribute, where:
     *
     * - `S` - the attribute is of type String
     * - `N` - the attribute is of type Number
     * - `B` - the attribute is of type Binary
     *
     * @var ScalarAttributeType::*|string
     */
    private $attributeType;

    /**
     * @param array{
     *   AttributeName: string,
     *   AttributeType: ScalarAttributeType::*|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attributeName = $input['AttributeName'] ?? $this->throwException(new InvalidArgument('Missing required field "AttributeName".'));
        $this->attributeType = $input['AttributeType'] ?? $this->throwException(new InvalidArgument('Missing required field "AttributeType".'));
    }

    /**
     * @param array{
     *   AttributeName: string,
     *   AttributeType: ScalarAttributeType::*|string,
     * }|AttributeDefinition $input
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
     * @return ScalarAttributeType::*|string
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
        $v = $this->attributeName;
        $payload['AttributeName'] = $v;
        $v = $this->attributeType;
        if (!ScalarAttributeType::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "AttributeType" for "%s". The value "%s" is not a valid "ScalarAttributeType".', __CLASS__, $v));
        }
        $payload['AttributeType'] = $v;

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
