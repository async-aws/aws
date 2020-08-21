<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ScalarAttributeType;

final class AttributeDefinition
{
    /**
     * A name for the attribute.
     */
    private $AttributeName;

    /**
     * The data type for the attribute, where:.
     */
    private $AttributeType;

    /**
     * @param array{
     *   AttributeName: string,
     *   AttributeType: ScalarAttributeType::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->AttributeName = $input['AttributeName'] ?? null;
        $this->AttributeType = $input['AttributeType'] ?? null;
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
     * @return ScalarAttributeType::*
     */
    public function getAttributeType(): string
    {
        return $this->AttributeType;
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
        if (null === $v = $this->AttributeType) {
            throw new InvalidArgument(sprintf('Missing parameter "AttributeType" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ScalarAttributeType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "AttributeType" for "%s". The value "%s" is not a valid "ScalarAttributeType".', __CLASS__, $v));
        }
        $payload['AttributeType'] = $v;

        return $payload;
    }
}
