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
use AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue;

/**
 * Represents the input of a `PutItem` operation.
 */
final class PutItemInput extends Input
{
    /**
     * The name of the table to contain the item. You can also provide the Amazon Resource Name (ARN) of the table in this
     * parameter.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * A map of attribute name/value pairs, one for each attribute. Only the primary key attributes are required; you can
     * optionally provide other attribute name-value pairs for the item.
     *
     * You must provide all of the attributes for the primary key. For example, with a simple primary key, you only need to
     * provide a value for the partition key. For a composite primary key, you must provide both values for both the
     * partition key and the sort key.
     *
     * If you specify any attributes that are part of an index key, then the data types for those attributes must match
     * those of the schema in the table's attribute definition.
     *
     * Empty String and Binary attribute values are allowed. Attribute values of type String and Binary must have a length
     * greater than zero if the attribute is used as a key attribute for a table or index.
     *
     * For more information about primary keys, see Primary Key [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * Each element in the `Item` map is an `AttributeValue` object.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/HowItWorks.CoreComponents.html#HowItWorks.CoreComponents.PrimaryKey
     *
     * @required
     *
     * @var array<string, AttributeValue>|null
     */
    private $item;

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
     * Use `ReturnValues` if you want to get the item attributes as they appeared before they were updated with the
     * `PutItem` request. For `PutItem`, the valid values are:
     *
     * - `NONE` - If `ReturnValues` is not specified, or if its value is `NONE`, then nothing is returned. (This setting is
     *   the default for `ReturnValues`.)
     * - `ALL_OLD` - If `PutItem` overwrote an attribute name-value pair, then the content of the old item is returned.
     *
     * The values returned are strongly consistent.
     *
     * There is no additional cost associated with requesting a return value aside from the small network and processing
     * overhead of receiving a larger response. No read capacity units are consumed.
     *
     * > The `ReturnValues` parameter is used by several DynamoDB operations; however, `PutItem` does not recognize any
     * > values other than `NONE` or `ALL_OLD`.
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
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see ConditionalOperator [^1] in
     * the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var ConditionalOperator::*|null
     */
    private $conditionalOperator;

    /**
     * A condition that must be satisfied in order for a conditional `PutItem` operation to succeed.
     *
     * An expression can contain any of the following:
     *
     * - Functions: `attribute_exists | attribute_not_exists | attribute_type | contains | begins_with | size`
     *
     *   These function names are case-sensitive.
     * - Comparison operators: `= | <> | < | > | <= | >= | BETWEEN | IN `
     * - Logical operators: `AND | OR | NOT`
     *
     * For more information on condition expressions, see Condition Expressions [^1] in the *Amazon DynamoDB Developer
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
     * complete list of reserved words, see Reserved Words [^1] in the *Amazon DynamoDB Developer Guide*). To work around
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
     * For more information on expression attribute names, see Specifying Item Attributes [^2] in the *Amazon DynamoDB
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
     * wanted to check whether the value of the *ProductStatus* attribute was one of the following:
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
     * An optional parameter that returns the item attributes for a `PutItem` operation that failed a condition check.
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
     *   Item?: array<string, AttributeValue|array>,
     *   Expected?: array<string, ExpectedAttributeValue|array>|null,
     *   ReturnValues?: ReturnValue::*|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
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

        if (isset($input['Item'])) {
            $this->item = [];
            foreach ($input['Item'] as $key => $item) {
                $this->item[$key] = AttributeValue::create($item);
            }
        }

        if (isset($input['Expected'])) {
            $this->expected = [];
            foreach ($input['Expected'] as $key => $item) {
                $this->expected[$key] = ExpectedAttributeValue::create($item);
            }
        }
        $this->returnValues = $input['ReturnValues'] ?? null;
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->returnItemCollectionMetrics = $input['ReturnItemCollectionMetrics'] ?? null;
        $this->conditionalOperator = $input['ConditionalOperator'] ?? null;
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
     *   Item?: array<string, AttributeValue|array>,
     *   Expected?: array<string, ExpectedAttributeValue|array>|null,
     *   ReturnValues?: ReturnValue::*|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     *   '@region'?: string|null,
     * }|PutItemInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
    public function getItem(): array
    {
        return $this->item ?? [];
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

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.PutItem',
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
    public function setItem(array $value): self
    {
        $this->item = $value;

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

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->tableName) {
            throw new InvalidArgument(\sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->item) {
            throw new InvalidArgument(\sprintf('Missing parameter "Item" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['Item'] = new \stdClass();
        } else {
            $payload['Item'] = [];
            foreach ($v as $name => $mv) {
                $payload['Item'][$name] = $mv->requestBody();
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
        if (null !== $v = $this->conditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
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
