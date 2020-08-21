<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ComparisonOperator;

final class ExpectedAttributeValue
{
    /**
     * Represents the data for the expected attribute.
     */
    private $Value;

    /**
     * Causes DynamoDB to evaluate the value before attempting a conditional operation:.
     */
    private $Exists;

    /**
     * A comparator for evaluating attributes in the `AttributeValueList`. For example, equals, greater than, less than,
     * etc.
     */
    private $ComparisonOperator;

    /**
     * One or more values to evaluate against the supplied attribute. The number of values in the list depends on the
     * `ComparisonOperator` being used.
     */
    private $AttributeValueList;

    /**
     * @param array{
     *   Value?: null|AttributeValue|array,
     *   Exists?: null|bool,
     *   ComparisonOperator?: null|ComparisonOperator::*,
     *   AttributeValueList?: null|AttributeValue[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Value = isset($input['Value']) ? AttributeValue::create($input['Value']) : null;
        $this->Exists = $input['Exists'] ?? null;
        $this->ComparisonOperator = $input['ComparisonOperator'] ?? null;
        $this->AttributeValueList = isset($input['AttributeValueList']) ? array_map([AttributeValue::class, 'create'], $input['AttributeValueList']) : null;
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
        return $this->AttributeValueList ?? [];
    }

    /**
     * @return ComparisonOperator::*|null
     */
    public function getComparisonOperator(): ?string
    {
        return $this->ComparisonOperator;
    }

    public function getExists(): ?bool
    {
        return $this->Exists;
    }

    public function getValue(): ?AttributeValue
    {
        return $this->Value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Value) {
            $payload['Value'] = $v->requestBody();
        }
        if (null !== $v = $this->Exists) {
            $payload['Exists'] = (bool) $v;
        }
        if (null !== $v = $this->ComparisonOperator) {
            if (!ComparisonOperator::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ComparisonOperator" for "%s". The value "%s" is not a valid "ComparisonOperator".', __CLASS__, $v));
            }
            $payload['ComparisonOperator'] = $v;
        }
        if (null !== $v = $this->AttributeValueList) {
            $index = -1;
            $payload['AttributeValueList'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AttributeValueList'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
