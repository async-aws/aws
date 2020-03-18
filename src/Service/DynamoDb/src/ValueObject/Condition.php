<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ComparisonOperator;

class Condition
{
    /**
     * One or more values to evaluate against the supplied attribute. The number of values in the list depends on the
     * `ComparisonOperator` being used.
     */
    private $AttributeValueList;

    /**
     * A comparator for evaluating attributes. For example, equals, greater than, less than, etc.
     */
    private $ComparisonOperator;

    /**
     * @param array{
     *   AttributeValueList?: null|\AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     *   ComparisonOperator: \AsyncAws\DynamoDb\Enum\ComparisonOperator::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->AttributeValueList = array_map([AttributeValue::class, 'create'], $input['AttributeValueList'] ?? []);
        $this->ComparisonOperator = $input['ComparisonOperator'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AttributeValue[]
     */
    public function getAttributeValueList(): array
    {
        return $this->AttributeValueList;
    }

    /**
     * @return ComparisonOperator::*
     */
    public function getComparisonOperator(): string
    {
        return $this->ComparisonOperator;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];

        $index = -1;
        foreach ($this->AttributeValueList as $listValue) {
            ++$index;
            $payload['AttributeValueList'][$index] = $listValue->requestBody();
        }

        if (null === $v = $this->ComparisonOperator) {
            throw new InvalidArgument(sprintf('Missing parameter "ComparisonOperator" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ComparisonOperator::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "ComparisonOperator" for "%s". The value "%s" is not a valid "ComparisonOperator".', __CLASS__, $v));
        }
        $payload['ComparisonOperator'] = $v;

        return $payload;
    }
}
