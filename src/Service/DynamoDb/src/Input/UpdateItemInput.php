<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ConditionalOperator;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics;
use AsyncAws\DynamoDb\Enum\ReturnValue;
use AsyncAws\DynamoDb\Enum\ReturnValuesOnConditionCheckFailure;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\AttributeValueUpdate;
use AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue;

/**
 * Represents the input of an `UpdateItem` operation.
 */
final class UpdateItemInput extends Input
{
    /**
     * The name of the table containing the item to update. You can also provide the Amazon Resource Name (ARN) of the table
     * in this parameter.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * The primary key of the item to be updated. Each element consists of an attribute name and a value for that attribute.
     *
     * For the primary key, you must provide all of the attributes. For example, with a simple primary key, you only need to
     * provide a value for the partition key. For a composite primary key, you must provide values for both the partition
     * key and the sort key.
     *
     * @required
     *
     * @var array<string, AttributeValue>|null
     */
    private $key;

    /**
     * This is a legacy parameter. Use `UpdateExpression` instead. For more information, see AttributeUpdates [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.AttributeUpdates.html
     *
     * @var array<string, AttributeValueUpdate>|null
     */
    private $attributeUpdates;

    /**
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see Expected [^1] in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.Expected.html
     *
     * @var array<string, ExpectedAttributeValue>|null
     */
    private $expected;

    /**
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see ConditionalOperator [^1] in
     * the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var ConditionalOperator::*|null
     */
    private $conditionalOperator;

    /**
     * Use `ReturnValues` if you want to get the item attributes as they appear before or after they are successfully
     * updated. For `UpdateItem`, the valid values are:
     *
     * - `NONE` - If `ReturnValues` is not specified, or if its value is `NONE`, then nothing is returned. (This setting is
     *   the default for `ReturnValues`.)
     * - `ALL_OLD` - Returns all of the attributes of the item, as they appeared before the UpdateItem operation.
     * - `UPDATED_OLD` - Returns only the updated attributes, as they appeared before the UpdateItem operation.
     * - `ALL_NEW` - Returns all of the attributes of the item, as they appear after the UpdateItem operation.
     * - `UPDATED_NEW` - Returns only the updated attributes, as they appear after the UpdateItem operation.
     *
     * There is no additional cost associated with requesting a return value aside from the small network and processing
     * overhead of receiving a larger response. No read capacity units are consumed.
     *
     * The values returned are strongly consistent.
     *
     * @var ReturnValue::*|null
     */
    private $returnValues;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $returnConsumedCapacity;

    /**
     * Determines whether item collection metrics are returned. If set to `SIZE`, the response includes statistics about
     * item collections, if any, that were modified during the operation are returned in the response. If set to `NONE` (the
     * default), no statistics are returned.
     *
     * @var ReturnItemCollectionMetrics::*|null
     */
    private $returnItemCollectionMetrics;

    /**
     * An expression that defines one or more attributes to be updated, the action to be performed on them, and new values
     * for them.
     *
     * The following action values are available for `UpdateExpression`.
     *
     * - `SET` - Adds one or more attributes and values to an item. If any of these attributes already exist, they are
     *   replaced by the new values. You can also use `SET` to add or subtract from an attribute that is of type Number. For
     *   example: `SET myNum = myNum + :val`
     *
     *   `SET` supports the following functions:
     *
     *   - `if_not_exists (path, operand)` - if the item does not contain an attribute at the specified path, then
     *     `if_not_exists` evaluates to operand; otherwise, it evaluates to path. You can use this function to avoid
     *     overwriting an attribute that may already be present in the item.
     *   - `list_append (operand, operand)` - evaluates to a list with a new element added to it. You can append the new
     *     element to the start or the end of the list by reversing the order of the operands.
     *
     *   These function names are case-sensitive.
     * - `REMOVE` - Removes one or more attributes from an item.
     * - `ADD` - Adds the specified value to the item, if the attribute does not already exist. If the attribute does exist,
     *   then the behavior of `ADD` depends on the data type of the attribute:
     *
     *   - If the existing attribute is a number, and if `Value` is also a number, then `Value` is mathematically added to
     *     the existing attribute. If `Value` is a negative number, then it is subtracted from the existing attribute.
     *
     *     > If you use `ADD` to increment or decrement a number value for an item that doesn't exist before the update,
     *     > DynamoDB uses `0` as the initial value.
     *     >
     *     > Similarly, if you use `ADD` for an existing item to increment or decrement an attribute value that doesn't
     *     > exist before the update, DynamoDB uses `0` as the initial value. For example, suppose that the item you want to
     *     > update doesn't have an attribute named `itemcount`, but you decide to `ADD` the number `3` to this attribute
     *     > anyway. DynamoDB will create the `itemcount` attribute, set its initial value to `0`, and finally add `3` to
     *     > it. The result will be a new `itemcount` attribute in the item, with a value of `3`.
     *
     *   - If the existing data type is a set and if `Value` is also a set, then `Value` is added to the existing set. For
     *     example, if the attribute value is the set `[1,2]`, and the `ADD` action specified `[3]`, then the final
     *     attribute value is `[1,2,3]`. An error occurs if an `ADD` action is specified for a set attribute and the
     *     attribute type specified does not match the existing set type.
     *
     *     Both sets must have the same primitive data type. For example, if the existing data type is a set of strings, the
     *     `Value` must also be a set of strings.
     *
     *   ! The `ADD` action only supports Number and set data types. In addition, `ADD` can only be used on top-level
     *   ! attributes, not nested attributes.
     *
     * - `DELETE` - Deletes an element from a set.
     *
     *   If a set of values is specified, then those values are subtracted from the old set. For example, if the attribute
     *   value was the set `[a,b,c]` and the `DELETE` action specifies `[a,c]`, then the final attribute value is `[b]`.
     *   Specifying an empty set is an error.
     *
     *   ! The `DELETE` action only supports set data types. In addition, `DELETE` can only be used on top-level attributes,
     *   ! not nested attributes.
     *
     *
     * You can have many actions in a single expression, such as the following: `SET a=:value1, b=:value2 DELETE :value3,
     * :value4, :value5`
     *
     * For more information on update expressions, see Modifying Items and Attributes [^1] in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.Modifying.html
     *
     * @var string|null
     */
    private $updateExpression;

    /**
     * A condition that must be satisfied in order for a conditional update to succeed.
     *
     * An expression can contain any of the following:
     *
     * - Functions: `attribute_exists | attribute_not_exists | attribute_type | contains | begins_with | size`
     *
     *   These function names are case-sensitive.
     * - Comparison operators: `= | <> | < | > | <= | >= | BETWEEN | IN `
     * - Logical operators: `AND | OR | NOT`
     *
     * For more information about condition expressions, see Specifying Conditions [^1] in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.SpecifyingConditions.html
     *
     * @var string|null
     */
    private $conditionExpression;

    /**
     * One or more substitution tokens for attribute names in an expression. The following are some use cases for using
     * `ExpressionAttributeNames`:
     *
     * - To access an attribute whose name conflicts with a DynamoDB reserved word.
     * - To create a placeholder for repeating occurrences of an attribute name in an expression.
     * - To prevent special characters in an attribute name from being misinterpreted in an expression.
     *
     * Use the **#** character in an expression to dereference an attribute name. For example, consider the following
     * attribute name:
     *
     * - `Percentile`
     *
     * The name of this attribute conflicts with a reserved word, so it cannot be used directly in an expression. (For the
     * complete list of reserved words, see Reserved Words [^1] in the *Amazon DynamoDB Developer Guide*.) To work around
     * this, you could specify the following for `ExpressionAttributeNames`:
     *
     * - `{"#P":"Percentile"}`
     *
     * You could then use this substitution in an expression, as in this example:
     *
     * - `#P = :val`
     *
     * > Tokens that begin with the **:** character are *expression attribute values*, which are placeholders for the actual
     * > value at runtime.
     *
     * For more information about expression attribute names, see Specifying Item Attributes [^2] in the *Amazon DynamoDB
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ReservedWords.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.AccessingItemAttributes.html
     *
     * @var array<string, string>|null
     */
    private $expressionAttributeNames;

    /**
     * One or more values that can be substituted in an expression.
     *
     * Use the **:** (colon) character in an expression to dereference an attribute value. For example, suppose that you
     * wanted to check whether the value of the `ProductStatus` attribute was one of the following:
     *
     * `Available | Backordered | Discontinued`
     *
     * You would first need to specify `ExpressionAttributeValues` as follows:
     *
     * `{ ":avail":{"S":"Available"}, ":back":{"S":"Backordered"}, ":disc":{"S":"Discontinued"} }`
     *
     * You could then use these values in an expression, such as this:
     *
     * `ProductStatus IN (:avail, :back, :disc)`
     *
     * For more information on expression attribute values, see Condition Expressions [^1] in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.SpecifyingConditions.html
     *
     * @var array<string, AttributeValue>|null
     */
    private $expressionAttributeValues;

    /**
     * An optional parameter that returns the item attributes for an `UpdateItem` operation that failed a condition check.
     *
     * There is no additional cost associated with requesting a return value aside from the small network and processing
     * overhead of receiving a larger response. No read capacity units are consumed.
     *
     * @var ReturnValuesOnConditionCheckFailure::*|null
     */
    private $returnValuesOnConditionCheckFailure;

    /**
     * @param array{
     *   TableName?: string,
     *   Key?: array<string, AttributeValue|array>,
     *   AttributeUpdates?: array<string, AttributeValueUpdate|array>|null,
     *   Expected?: array<string, ExpectedAttributeValue|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ReturnValues?: ReturnValue::*|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   UpdateExpression?: string|null,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->tableName = $input['TableName'] ?? null;

        if (isset($input['Key'])) {
            $this->key = [];
            foreach ($input['Key'] as $key => $item) {
                $this->key[$key] = AttributeValue::create($item);
            }
        }

        if (isset($input['AttributeUpdates'])) {
            $this->attributeUpdates = [];
            foreach ($input['AttributeUpdates'] as $key => $item) {
                $this->attributeUpdates[$key] = AttributeValueUpdate::create($item);
            }
        }

        if (isset($input['Expected'])) {
            $this->expected = [];
            foreach ($input['Expected'] as $key => $item) {
                $this->expected[$key] = ExpectedAttributeValue::create($item);
            }
        }
        $this->conditionalOperator = $input['ConditionalOperator'] ?? null;
        $this->returnValues = $input['ReturnValues'] ?? null;
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->returnItemCollectionMetrics = $input['ReturnItemCollectionMetrics'] ?? null;
        $this->updateExpression = $input['UpdateExpression'] ?? null;
        $this->conditionExpression = $input['ConditionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;

        if (isset($input['ExpressionAttributeValues'])) {
            $this->expressionAttributeValues = [];
            foreach ($input['ExpressionAttributeValues'] as $key => $item) {
                $this->expressionAttributeValues[$key] = AttributeValue::create($item);
            }
        }
        $this->returnValuesOnConditionCheckFailure = $input['ReturnValuesOnConditionCheckFailure'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TableName?: string,
     *   Key?: array<string, AttributeValue|array>,
     *   AttributeUpdates?: array<string, AttributeValueUpdate|array>|null,
     *   Expected?: array<string, ExpectedAttributeValue|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ReturnValues?: ReturnValue::*|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   UpdateExpression?: string|null,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * }|UpdateItemInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValueUpdate>
     */
    public function getAttributeUpdates(): array
    {
        return $this->attributeUpdates ?? [];
    }

    public function getConditionExpression(): ?string
    {
        return $this->conditionExpression;
    }

    /**
     * @return ConditionalOperator::*|null
     */
    public function getConditionalOperator(): ?string
    {
        return $this->conditionalOperator;
    }

    /**
     * @return array<string, ExpectedAttributeValue>
     */
    public function getExpected(): array
    {
        return $this->expected ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getExpressionAttributeNames(): array
    {
        return $this->expressionAttributeNames ?? [];
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getExpressionAttributeValues(): array
    {
        return $this->expressionAttributeValues ?? [];
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getKey(): array
    {
        return $this->key ?? [];
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->returnConsumedCapacity;
    }

    /**
     * @return ReturnItemCollectionMetrics::*|null
     */
    public function getReturnItemCollectionMetrics(): ?string
    {
        return $this->returnItemCollectionMetrics;
    }

    /**
     * @return ReturnValue::*|null
     */
    public function getReturnValues(): ?string
    {
        return $this->returnValues;
    }

    /**
     * @return ReturnValuesOnConditionCheckFailure::*|null
     */
    public function getReturnValuesOnConditionCheckFailure(): ?string
    {
        return $this->returnValuesOnConditionCheckFailure;
    }

    public function getTableName(): ?string
    {
        return $this->tableName;
    }

    public function getUpdateExpression(): ?string
    {
        return $this->updateExpression;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.UpdateItem',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, AttributeValueUpdate> $value
     */
    public function setAttributeUpdates(array $value): self
    {
        $this->attributeUpdates = $value;

        return $this;
    }

    public function setConditionExpression(?string $value): self
    {
        $this->conditionExpression = $value;

        return $this;
    }

    /**
     * @param ConditionalOperator::*|null $value
     */
    public function setConditionalOperator(?string $value): self
    {
        $this->conditionalOperator = $value;

        return $this;
    }

    /**
     * @param array<string, ExpectedAttributeValue> $value
     */
    public function setExpected(array $value): self
    {
        $this->expected = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setExpressionAttributeNames(array $value): self
    {
        $this->expressionAttributeNames = $value;

        return $this;
    }

    /**
     * @param array<string, AttributeValue> $value
     */
    public function setExpressionAttributeValues(array $value): self
    {
        $this->expressionAttributeValues = $value;

        return $this;
    }

    /**
     * @param array<string, AttributeValue> $value
     */
    public function setKey(array $value): self
    {
        $this->key = $value;

        return $this;
    }

    /**
     * @param ReturnConsumedCapacity::*|null $value
     */
    public function setReturnConsumedCapacity(?string $value): self
    {
        $this->returnConsumedCapacity = $value;

        return $this;
    }

    /**
     * @param ReturnItemCollectionMetrics::*|null $value
     */
    public function setReturnItemCollectionMetrics(?string $value): self
    {
        $this->returnItemCollectionMetrics = $value;

        return $this;
    }

    /**
     * @param ReturnValue::*|null $value
     */
    public function setReturnValues(?string $value): self
    {
        $this->returnValues = $value;

        return $this;
    }

    /**
     * @param ReturnValuesOnConditionCheckFailure::*|null $value
     */
    public function setReturnValuesOnConditionCheckFailure(?string $value): self
    {
        $this->returnValuesOnConditionCheckFailure = $value;

        return $this;
    }

    public function setTableName(?string $value): self
    {
        $this->tableName = $value;

        return $this;
    }

    public function setUpdateExpression(?string $value): self
    {
        $this->updateExpression = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->tableName) {
            throw new InvalidArgument(\sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->key) {
            throw new InvalidArgument(\sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['Key'] = new \stdClass();
        } else {
            $payload['Key'] = [];
            foreach ($v as $name => $mv) {
                $payload['Key'][$name] = $mv->requestBody();
            }
        }
        if (null !== $v = $this->attributeUpdates) {
            if (empty($v)) {
                $payload['AttributeUpdates'] = new \stdClass();
            } else {
                $payload['AttributeUpdates'] = [];
                foreach ($v as $name => $mv) {
                    $payload['AttributeUpdates'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->expected) {
            if (empty($v)) {
                $payload['Expected'] = new \stdClass();
            } else {
                $payload['Expected'] = [];
                foreach ($v as $name => $mv) {
                    $payload['Expected'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->conditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
        }
        if (null !== $v = $this->returnValues) {
            if (!ReturnValue::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnValues" for "%s". The value "%s" is not a valid "ReturnValue".', __CLASS__, $v));
            }
            $payload['ReturnValues'] = $v;
        }
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->returnItemCollectionMetrics) {
            if (!ReturnItemCollectionMetrics::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnItemCollectionMetrics" for "%s". The value "%s" is not a valid "ReturnItemCollectionMetrics".', __CLASS__, $v));
            }
            $payload['ReturnItemCollectionMetrics'] = $v;
        }
        if (null !== $v = $this->updateExpression) {
            $payload['UpdateExpression'] = $v;
        }
        if (null !== $v = $this->conditionExpression) {
            $payload['ConditionExpression'] = $v;
        }
        if (null !== $v = $this->expressionAttributeNames) {
            if (empty($v)) {
                $payload['ExpressionAttributeNames'] = new \stdClass();
            } else {
                $payload['ExpressionAttributeNames'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ExpressionAttributeNames'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->expressionAttributeValues) {
            if (empty($v)) {
                $payload['ExpressionAttributeValues'] = new \stdClass();
            } else {
                $payload['ExpressionAttributeValues'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ExpressionAttributeValues'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->returnValuesOnConditionCheckFailure) {
            if (!ReturnValuesOnConditionCheckFailure::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnValuesOnConditionCheckFailure" for "%s". The value "%s" is not a valid "ReturnValuesOnConditionCheckFailure".', __CLASS__, $v));
            }
            $payload['ReturnValuesOnConditionCheckFailure'] = $v;
        }

        return $payload;
    }
}
