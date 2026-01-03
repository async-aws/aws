<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ComparisonOperator;

/**
 * Represents a condition to be compared with an attribute value. This condition can be used with `DeleteItem`,
 * `PutItem`, or `UpdateItem` operations; if the comparison evaluates to true, the operation succeeds; if not, the
 * operation fails. You can use `ExpectedAttributeValue` in one of two different ways:
 *
 * - Use `AttributeValueList` to specify one or more values to compare against an attribute. Use `ComparisonOperator` to
 *   specify how you want to perform the comparison. If the comparison evaluates to true, then the conditional operation
 *   succeeds.
 * - Use `Value` to specify a value that DynamoDB will compare against an attribute. If the values match, then
 *   `ExpectedAttributeValue` evaluates to true and the conditional operation succeeds. Optionally, you can also set
 *   `Exists` to false, indicating that you *do not* expect to find the attribute value in the table. In this case, the
 *   conditional operation succeeds only if the comparison evaluates to false.
 *
 * `Value` and `Exists` are incompatible with `AttributeValueList` and `ComparisonOperator`. Note that if you use both
 * sets of parameters at once, DynamoDB will return a `ValidationException` exception.
 */
final class ExpectedAttributeValue
{
    /**
     * Represents the data for the expected attribute.
     *
     * Each attribute value is described as a name-value pair. The name is the data type, and the value is the data itself.
     *
     * For more information, see Data Types [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.NamingRulesDataTypes.html#HowItWorks.DataTypes
     *
     * @var AttributeValue|null
     */
    private $value;

    /**
     * Causes DynamoDB to evaluate the value before attempting a conditional operation:
     *
     * - If `Exists` is `true`, DynamoDB will check to see if that attribute value already exists in the table. If it is
     *   found, then the operation succeeds. If it is not found, the operation fails with a `ConditionCheckFailedException`.
     * - If `Exists` is `false`, DynamoDB assumes that the attribute value does not exist in the table. If in fact the value
     *   does not exist, then the assumption is valid and the operation succeeds. If the value is found, despite the
     *   assumption that it does not exist, the operation fails with a `ConditionCheckFailedException`.
     *
     * The default setting for `Exists` is `true`. If you supply a `Value` all by itself, DynamoDB assumes the attribute
     * exists: You don't have to set `Exists` to `true`, because it is implied.
     *
     * DynamoDB returns a `ValidationException` if:
     *
     * - `Exists` is `true` but there is no `Value` to check. (You expect a value to exist, but don't specify what that
     *   value is.)
     * - `Exists` is `false` but you also provide a `Value`. (You cannot expect an attribute to have a value, while also
     *   expecting it not to exist.)
     *
     * @var bool|null
     */
    private $exists;

    /**
     * A comparator for evaluating attributes in the `AttributeValueList`. For example, equals, greater than, less than,
     * etc.
     *
     * The following comparison operators are available:
     *
     * `EQ | NE | LE | LT | GE | GT | NOT_NULL | NULL | CONTAINS | NOT_CONTAINS | BEGINS_WITH | IN | BETWEEN`
     *
     * The following are descriptions of each comparison operator.
     *
     * - `EQ` : Equal. `EQ` is supported for all data types, including lists and maps.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` element of type String, Number, Binary, String Set,
     *   Number Set, or Binary Set. If an item contains an `AttributeValue` element of a different type than the one
     *   provided in the request, the value does not match. For example, `{"S":"6"}` does not equal `{"N":"6"}`. Also,
     *   `{"N":"6"}` does not equal `{"NS":["6", "2", "1"]}`.
     * - `NE` : Not equal. `NE` is supported for all data types, including lists and maps.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` of type String, Number, Binary, String Set, Number Set,
     *   or Binary Set. If an item contains an `AttributeValue` of a different type than the one provided in the request,
     *   the value does not match. For example, `{"S":"6"}` does not equal `{"N":"6"}`. Also, `{"N":"6"}` does not equal
     *   `{"NS":["6", "2", "1"]}`.
     * - `LE` : Less than or equal.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` element of type String, Number, or Binary (not a set
     *   type). If an item contains an `AttributeValue` element of a different type than the one provided in the request,
     *   the value does not match. For example, `{"S":"6"}` does not equal `{"N":"6"}`. Also, `{"N":"6"}` does not compare
     *   to `{"NS":["6", "2", "1"]}`.
     * - `LT` : Less than.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` of type String, Number, or Binary (not a set type). If
     *   an item contains an `AttributeValue` element of a different type than the one provided in the request, the value
     *   does not match. For example, `{"S":"6"}` does not equal `{"N":"6"}`. Also, `{"N":"6"}` does not compare to
     *   `{"NS":["6", "2", "1"]}`.
     * - `GE` : Greater than or equal.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` element of type String, Number, or Binary (not a set
     *   type). If an item contains an `AttributeValue` element of a different type than the one provided in the request,
     *   the value does not match. For example, `{"S":"6"}` does not equal `{"N":"6"}`. Also, `{"N":"6"}` does not compare
     *   to `{"NS":["6", "2", "1"]}`.
     * - `GT` : Greater than.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` element of type String, Number, or Binary (not a set
     *   type). If an item contains an `AttributeValue` element of a different type than the one provided in the request,
     *   the value does not match. For example, `{"S":"6"}` does not equal `{"N":"6"}`. Also, `{"N":"6"}` does not compare
     *   to `{"NS":["6", "2", "1"]}`.
     * - `NOT_NULL` : The attribute exists. `NOT_NULL` is supported for all data types, including lists and maps.
     *
     *   > This operator tests for the existence of an attribute, not its data type. If the data type of attribute "`a`" is
     *   > null, and you evaluate it using `NOT_NULL`, the result is a Boolean `true`. This result is because the attribute
     *   > "`a`" exists; its data type is not relevant to the `NOT_NULL` comparison operator.
     *
     * - `NULL` : The attribute does not exist. `NULL` is supported for all data types, including lists and maps.
     *
     *   > This operator tests for the nonexistence of an attribute, not its data type. If the data type of attribute "`a`"
     *   > is null, and you evaluate it using `NULL`, the result is a Boolean `false`. This is because the attribute "`a`"
     *   > exists; its data type is not relevant to the `NULL` comparison operator.
     *
     * - `CONTAINS` : Checks for a subsequence, or value in a set.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` element of type String, Number, or Binary (not a set
     *   type). If the target attribute of the comparison is of type String, then the operator checks for a substring match.
     *   If the target attribute of the comparison is of type Binary, then the operator looks for a subsequence of the
     *   target that matches the input. If the target attribute of the comparison is a set ("`SS`", "`NS`", or "`BS`"), then
     *   the operator evaluates to true if it finds an exact match with any member of the set.
     *
     *   CONTAINS is supported for lists: When evaluating "`a CONTAINS b`", "`a`" can be a list; however, "`b`" cannot be a
     *   set, a map, or a list.
     * - `NOT_CONTAINS` : Checks for absence of a subsequence, or absence of a value in a set.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` element of type String, Number, or Binary (not a set
     *   type). If the target attribute of the comparison is a String, then the operator checks for the absence of a
     *   substring match. If the target attribute of the comparison is Binary, then the operator checks for the absence of a
     *   subsequence of the target that matches the input. If the target attribute of the comparison is a set ("`SS`",
     *   "`NS`", or "`BS`"), then the operator evaluates to true if it *does not* find an exact match with any member of the
     *   set.
     *
     *   NOT_CONTAINS is supported for lists: When evaluating "`a NOT CONTAINS b`", "`a`" can be a list; however, "`b`"
     *   cannot be a set, a map, or a list.
     * - `BEGINS_WITH` : Checks for a prefix.
     *
     *   `AttributeValueList` can contain only one `AttributeValue` of type String or Binary (not a Number or a set type).
     *   The target attribute of the comparison must be of type String or Binary (not a Number or a set type).
     * - `IN` : Checks for matching elements in a list.
     *
     *   `AttributeValueList` can contain one or more `AttributeValue` elements of type String, Number, or Binary. These
     *   attributes are compared against an existing attribute of an item. If any elements of the input are equal to the
     *   item attribute, the expression evaluates to true.
     * - `BETWEEN` : Greater than or equal to the first value, and less than or equal to the second value.
     *
     *   `AttributeValueList` must contain two `AttributeValue` elements of the same type, either String, Number, or Binary
     *   (not a set type). A target attribute matches if the target value is greater than, or equal to, the first element
     *   and less than, or equal to, the second element. If an item contains an `AttributeValue` element of a different type
     *   than the one provided in the request, the value does not match. For example, `{"S":"6"}` does not compare to
     *   `{"N":"6"}`. Also, `{"N":"6"}` does not compare to `{"NS":["6", "2", "1"]}`
     *
     * @var ComparisonOperator::*|null
     */
    private $comparisonOperator;

    /**
     * One or more values to evaluate against the supplied attribute. The number of values in the list depends on the
     * `ComparisonOperator` being used.
     *
     * For type Number, value comparisons are numeric.
     *
     * String value comparisons for greater than, equals, or less than are based on ASCII character code values. For
     * example, `a` is greater than `A`, and `a` is greater than `B`. For a list of code values, see
     * http://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters [^1].
     *
     * For Binary, DynamoDB treats each byte of the binary data as unsigned when it compares binary values.
     *
     * For information on specifying data types in JSON, see JSON Data Format [^2] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: http://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/DataFormat.html
     *
     * @var AttributeValue[]|null
     */
    private $attributeValueList;

    /**
     * @param array{
     *   Value?: AttributeValue|array|null,
     *   Exists?: bool|null,
     *   ComparisonOperator?: ComparisonOperator::*|null,
     *   AttributeValueList?: array<AttributeValue|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->value = isset($input['Value']) ? AttributeValue::create($input['Value']) : null;
        $this->exists = $input['Exists'] ?? null;
        $this->comparisonOperator = $input['ComparisonOperator'] ?? null;
        $this->attributeValueList = isset($input['AttributeValueList']) ? array_map([AttributeValue::class, 'create'], $input['AttributeValueList']) : null;
    }

    /**
     * @param array{
     *   Value?: AttributeValue|array|null,
     *   Exists?: bool|null,
     *   ComparisonOperator?: ComparisonOperator::*|null,
     *   AttributeValueList?: array<AttributeValue|array>|null,
     * }|ExpectedAttributeValue $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return AttributeValue[]
     */
    public function getAttributeValueList(): array
    {
        return $this->attributeValueList ?? [];
    }

    /**
     * @return ComparisonOperator::*|null
     */
    public function getComparisonOperator(): ?string
    {
        return $this->comparisonOperator;
    }

    public function getExists(): ?bool
    {
        return $this->exists;
    }

    public function getValue(): ?AttributeValue
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->value) {
            $payload['Value'] = $v->requestBody();
        }
        if (null !== $v = $this->exists) {
            $payload['Exists'] = (bool) $v;
        }
        if (null !== $v = $this->comparisonOperator) {
            if (!ComparisonOperator::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ComparisonOperator" for "%s". The value "%s" is not a valid "ComparisonOperator".', __CLASS__, $v));
            }
            $payload['ComparisonOperator'] = $v;
        }
        if (null !== $v = $this->attributeValueList) {
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
