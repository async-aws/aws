<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ComparisonOperator;

/**
 * Represents the selection criteria for a `Query` or `Scan` operation:.
 *
 * - For a `Query` operation, `Condition` is used for specifying the `KeyConditions` to use when querying a table or an
 *   index. For `KeyConditions`, only the following comparison operators are supported:
 *   `EQ | LE | LT | GE | GT | BEGINS_WITH | BETWEEN`
 *   `Condition` is also used in a `QueryFilter`, which evaluates the query results and returns only the desired values.
 * - For a `Scan` operation, `Condition` is used in a `ScanFilter`, which evaluates the scan results and returns only
 *   the desired values.
 */
final class Condition
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
     *   AttributeValueList?: null|AttributeValue[],
     *   ComparisonOperator: ComparisonOperator::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->AttributeValueList = isset($input['AttributeValueList']) ? array_map([AttributeValue::class, 'create'], $input['AttributeValueList']) : null;
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
        return $this->AttributeValueList ?? [];
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
        if (null !== $v = $this->AttributeValueList) {
            $index = -1;
            $payload['AttributeValueList'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AttributeValueList'][$index] = $listValue->requestBody();
            }
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
