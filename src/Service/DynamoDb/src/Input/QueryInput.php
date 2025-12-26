<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ConditionalOperator;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\Select;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Condition;

/**
 * Represents the input of a `Query` operation.
 */
final class QueryInput extends Input
{
    /**
     * The name of the table containing the requested items. You can also provide the Amazon Resource Name (ARN) of the
     * table in this parameter.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * The name of an index to query. This index can be any local secondary index or global secondary index on the table.
     * Note that if you use the `IndexName` parameter, you must also provide `TableName.`.
     *
     * @var string|null
     */
    private $indexName;

    /**
     * The attributes to be returned in the result. You can retrieve all item attributes, specific item attributes, the
     * count of matching items, or in the case of an index, some or all of the attributes projected into the index.
     *
     * - `ALL_ATTRIBUTES` - Returns all of the item attributes from the specified table or index. If you query a local
     *   secondary index, then for each matching item in the index, DynamoDB fetches the entire item from the parent table.
     *   If the index is configured to project all item attributes, then all of the data can be obtained from the local
     *   secondary index, and no fetching is required.
     * - `ALL_PROJECTED_ATTRIBUTES` - Allowed only when querying an index. Retrieves all attributes that have been projected
     *   into the index. If the index is configured to project all attributes, this return value is equivalent to specifying
     *   `ALL_ATTRIBUTES`.
     * - `COUNT` - Returns the number of matching items, rather than the matching items themselves. Note that this uses the
     *   same quantity of read capacity units as getting the items, and is subject to the same item size calculations.
     * - `SPECIFIC_ATTRIBUTES` - Returns only the attributes listed in `ProjectionExpression`. This return value is
     *   equivalent to specifying `ProjectionExpression` without specifying any value for `Select`.
     *
     *   If you query or scan a local secondary index and request only attributes that are projected into that index, the
     *   operation will read only the index and not the table. If any of the requested attributes are not projected into the
     *   local secondary index, DynamoDB fetches each of these attributes from the parent table. This extra fetching incurs
     *   additional throughput cost and latency.
     *
     *   If you query or scan a global secondary index, you can only request attributes that are projected into the index.
     *   Global secondary index queries cannot fetch attributes from the parent table.
     *
     * If neither `Select` nor `ProjectionExpression` are specified, DynamoDB defaults to `ALL_ATTRIBUTES` when accessing a
     * table, and `ALL_PROJECTED_ATTRIBUTES` when accessing an index. You cannot use both `Select` and
     * `ProjectionExpression` together in a single request, unless the value for `Select` is `SPECIFIC_ATTRIBUTES`. (This
     * usage is equivalent to specifying `ProjectionExpression` without any value for `Select`.)
     *
     * > If you use the `ProjectionExpression` parameter, then the value for `Select` can only be `SPECIFIC_ATTRIBUTES`. Any
     * > other value for `Select` will return an error.
     *
     * @var Select::*|null
     */
    private $select;

    /**
     * This is a legacy parameter. Use `ProjectionExpression` instead. For more information, see AttributesToGet [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.AttributesToGet.html
     *
     * @var string[]|null
     */
    private $attributesToGet;

    /**
     * The maximum number of items to evaluate (not necessarily the number of matching items). If DynamoDB processes the
     * number of items up to the limit while processing the results, it stops the operation and returns the matching values
     * up to that point, and a key in `LastEvaluatedKey` to apply in a subsequent operation, so that you can pick up where
     * you left off. Also, if the processed dataset size exceeds 1 MB before DynamoDB reaches this limit, it stops the
     * operation and returns the matching values up to the limit, and a key in `LastEvaluatedKey` to apply in a subsequent
     * operation to continue the operation. For more information, see Query and Scan [^1] in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/QueryAndScan.html
     *
     * @var int|null
     */
    private $limit;

    /**
     * Determines the read consistency model: If set to `true`, then the operation uses strongly consistent reads;
     * otherwise, the operation uses eventually consistent reads.
     *
     * Strongly consistent reads are not supported on global secondary indexes. If you query a global secondary index with
     * `ConsistentRead` set to `true`, you will receive a `ValidationException`.
     *
     * @var bool|null
     */
    private $consistentRead;

    /**
     * This is a legacy parameter. Use `KeyConditionExpression` instead. For more information, see KeyConditions [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.KeyConditions.html
     *
     * @var array<string, Condition>|null
     */
    private $keyConditions;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see QueryFilter [^1] in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.QueryFilter.html
     *
     * @var array<string, Condition>|null
     */
    private $queryFilter;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see ConditionalOperator [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var ConditionalOperator::*|null
     */
    private $conditionalOperator;

    /**
     * Specifies the order for index traversal: If `true` (default), the traversal is performed in ascending order; if
     * `false`, the traversal is performed in descending order.
     *
     * Items with the same partition key value are stored in sorted order by sort key. If the sort key data type is Number,
     * the results are stored in numeric order. For type String, the results are stored in order of UTF-8 bytes. For type
     * Binary, DynamoDB treats each byte of the binary data as unsigned.
     *
     * If `ScanIndexForward` is `true`, DynamoDB returns the results in the order in which they are stored (by sort key
     * value). This is the default behavior. If `ScanIndexForward` is `false`, DynamoDB reads the results in reverse order
     * by sort key value, and then returns the results to the client.
     *
     * @var bool|null
     */
    private $scanIndexForward;

    /**
     * The primary key of the first item that this operation will evaluate. Use the value that was returned for
     * `LastEvaluatedKey` in the previous operation.
     *
     * The data type for `ExclusiveStartKey` must be String, Number, or Binary. No set data types are allowed.
     *
     * @var array<string, AttributeValue>|null
     */
    private $exclusiveStartKey;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $returnConsumedCapacity;

    /**
     * A string that identifies one or more attributes to retrieve from the table. These attributes can include scalars,
     * sets, or elements of a JSON document. The attributes in the expression must be separated by commas.
     *
     * If no attribute names are specified, then all attributes will be returned. If any of the requested attributes are not
     * found, they will not appear in the result.
     *
     * For more information, see Accessing Item Attributes [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.AccessingItemAttributes.html
     *
     * @var string|null
     */
    private $projectionExpression;

    /**
     * A string that contains conditions that DynamoDB applies after the `Query` operation, but before the data is returned
     * to you. Items that do not satisfy the `FilterExpression` criteria are not returned.
     *
     * A `FilterExpression` does not allow key attributes. You cannot define a filter expression based on a partition key or
     * a sort key.
     *
     * > A `FilterExpression` is applied after the items have already been read; the process of filtering does not consume
     * > any additional read capacity units.
     *
     * For more information, see Filter Expressions [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Query.FilterExpression.html
     *
     * @var string|null
     */
    private $filterExpression;

    /**
     * The condition that specifies the key values for items to be retrieved by the `Query` action.
     *
     * The condition must perform an equality test on a single partition key value.
     *
     * The condition can optionally perform one of several comparison tests on a single sort key value. This allows `Query`
     * to retrieve one item with a given partition key value and sort key value, or several items that have the same
     * partition key value but different sort key values.
     *
     * The partition key equality test is required, and must be specified in the following format:
     *
     * `partitionKeyName`*=*`:partitionkeyval`
     *
     * If you also want to provide a condition for the sort key, it must be combined using `AND` with the condition for the
     * sort key. Following is an example, using the **=** comparison operator for the sort key:
     *
     * `partitionKeyName``=``:partitionkeyval``AND``sortKeyName``=``:sortkeyval`
     *
     * Valid comparisons for the sort key condition are as follows:
     *
     * - `sortKeyName``=``:sortkeyval` - true if the sort key value is equal to `:sortkeyval`.
     * - `sortKeyName``<``:sortkeyval` - true if the sort key value is less than `:sortkeyval`.
     * - `sortKeyName``<=``:sortkeyval` - true if the sort key value is less than or equal to `:sortkeyval`.
     * - `sortKeyName``>``:sortkeyval` - true if the sort key value is greater than `:sortkeyval`.
     * - `sortKeyName``>= ``:sortkeyval` - true if the sort key value is greater than or equal to `:sortkeyval`.
     * - `sortKeyName``BETWEEN``:sortkeyval1``AND``:sortkeyval2` - true if the sort key value is greater than or equal to
     *   `:sortkeyval1`, and less than or equal to `:sortkeyval2`.
     * - `begins_with (``sortKeyName`, `:sortkeyval``)` - true if the sort key value begins with a particular operand. (You
     *   cannot use this function with a sort key that is of type Number.) Note that the function name `begins_with` is
     *   case-sensitive.
     *
     * Use the `ExpressionAttributeValues` parameter to replace tokens such as `:partitionval` and `:sortval` with actual
     * values at runtime.
     *
     * You can optionally use the `ExpressionAttributeNames` parameter to replace the names of the partition key and sort
     * key with placeholder tokens. This option might be necessary if an attribute name conflicts with a DynamoDB reserved
     * word. For example, the following `KeyConditionExpression` parameter causes an error because *Size* is a reserved
     * word:
     *
     * - `Size = :myval`
     *
     * To work around this, define a placeholder (such a `#S`) to represent the attribute name *Size*.
     * `KeyConditionExpression` then is as follows:
     *
     * - `#S = :myval`
     *
     * For a list of reserved words, see Reserved Words [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * For more information on `ExpressionAttributeNames` and `ExpressionAttributeValues`, see Using Placeholders for
     * Attribute Names and Values [^2] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ReservedWords.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ExpressionPlaceholders.html
     *
     * @var string|null
     */
    private $keyConditionExpression;

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
     * For more information on expression attribute values, see Specifying Conditions [^1] in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.SpecifyingConditions.html
     *
     * @var array<string, AttributeValue>|null
     */
    private $expressionAttributeValues;

    /**
     * @param array{
     *   TableName?: string,
     *   IndexName?: string|null,
     *   Select?: Select::*|null,
     *   AttributesToGet?: string[]|null,
     *   Limit?: int|null,
     *   ConsistentRead?: bool|null,
     *   KeyConditions?: array<string, Condition|array>|null,
     *   QueryFilter?: array<string, Condition|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ScanIndexForward?: bool|null,
     *   ExclusiveStartKey?: array<string, AttributeValue|array>|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ProjectionExpression?: string|null,
     *   FilterExpression?: string|null,
     *   KeyConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->tableName = $input['TableName'] ?? null;
        $this->indexName = $input['IndexName'] ?? null;
        $this->select = $input['Select'] ?? null;
        $this->attributesToGet = $input['AttributesToGet'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->consistentRead = $input['ConsistentRead'] ?? null;

        if (isset($input['KeyConditions'])) {
            $this->keyConditions = [];
            foreach ($input['KeyConditions'] as $key => $item) {
                $this->keyConditions[$key] = Condition::create($item);
            }
        }

        if (isset($input['QueryFilter'])) {
            $this->queryFilter = [];
            foreach ($input['QueryFilter'] as $key => $item) {
                $this->queryFilter[$key] = Condition::create($item);
            }
        }
        $this->conditionalOperator = $input['ConditionalOperator'] ?? null;
        $this->scanIndexForward = $input['ScanIndexForward'] ?? null;

        if (isset($input['ExclusiveStartKey'])) {
            $this->exclusiveStartKey = [];
            foreach ($input['ExclusiveStartKey'] as $key => $item) {
                $this->exclusiveStartKey[$key] = AttributeValue::create($item);
            }
        }
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->projectionExpression = $input['ProjectionExpression'] ?? null;
        $this->filterExpression = $input['FilterExpression'] ?? null;
        $this->keyConditionExpression = $input['KeyConditionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;

        if (isset($input['ExpressionAttributeValues'])) {
            $this->expressionAttributeValues = [];
            foreach ($input['ExpressionAttributeValues'] as $key => $item) {
                $this->expressionAttributeValues[$key] = AttributeValue::create($item);
            }
        }
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TableName?: string,
     *   IndexName?: string|null,
     *   Select?: Select::*|null,
     *   AttributesToGet?: string[]|null,
     *   Limit?: int|null,
     *   ConsistentRead?: bool|null,
     *   KeyConditions?: array<string, Condition|array>|null,
     *   QueryFilter?: array<string, Condition|array>|null,
     *   ConditionalOperator?: ConditionalOperator::*|null,
     *   ScanIndexForward?: bool|null,
     *   ExclusiveStartKey?: array<string, AttributeValue|array>|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ProjectionExpression?: string|null,
     *   FilterExpression?: string|null,
     *   KeyConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   '@region'?: string|null,
     * }|QueryInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getAttributesToGet(): array
    {
        return $this->attributesToGet ?? [];
    }

    /**
     * @return ConditionalOperator::*|null
     */
    public function getConditionalOperator(): ?string
    {
        return $this->conditionalOperator;
    }

    public function getConsistentRead(): ?bool
    {
        return $this->consistentRead;
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getExclusiveStartKey(): array
    {
        return $this->exclusiveStartKey ?? [];
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

    public function getFilterExpression(): ?string
    {
        return $this->filterExpression;
    }

    public function getIndexName(): ?string
    {
        return $this->indexName;
    }

    public function getKeyConditionExpression(): ?string
    {
        return $this->keyConditionExpression;
    }

    /**
     * @return array<string, Condition>
     */
    public function getKeyConditions(): array
    {
        return $this->keyConditions ?? [];
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getProjectionExpression(): ?string
    {
        return $this->projectionExpression;
    }

    /**
     * @return array<string, Condition>
     */
    public function getQueryFilter(): array
    {
        return $this->queryFilter ?? [];
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->returnConsumedCapacity;
    }

    public function getScanIndexForward(): ?bool
    {
        return $this->scanIndexForward;
    }

    /**
     * @return Select::*|null
     */
    public function getSelect(): ?string
    {
        return $this->select;
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
            'X-Amz-Target' => 'DynamoDB_20120810.Query',
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
     * @param string[] $value
     */
    public function setAttributesToGet(array $value): self
    {
        $this->attributesToGet = $value;

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

    public function setConsistentRead(?bool $value): self
    {
        $this->consistentRead = $value;

        return $this;
    }

    /**
     * @param array<string, AttributeValue> $value
     */
    public function setExclusiveStartKey(array $value): self
    {
        $this->exclusiveStartKey = $value;

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

    public function setFilterExpression(?string $value): self
    {
        $this->filterExpression = $value;

        return $this;
    }

    public function setIndexName(?string $value): self
    {
        $this->indexName = $value;

        return $this;
    }

    public function setKeyConditionExpression(?string $value): self
    {
        $this->keyConditionExpression = $value;

        return $this;
    }

    /**
     * @param array<string, Condition> $value
     */
    public function setKeyConditions(array $value): self
    {
        $this->keyConditions = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->limit = $value;

        return $this;
    }

    public function setProjectionExpression(?string $value): self
    {
        $this->projectionExpression = $value;

        return $this;
    }

    /**
     * @param array<string, Condition> $value
     */
    public function setQueryFilter(array $value): self
    {
        $this->queryFilter = $value;

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

    public function setScanIndexForward(?bool $value): self
    {
        $this->scanIndexForward = $value;

        return $this;
    }

    /**
     * @param Select::*|null $value
     */
    public function setSelect(?string $value): self
    {
        $this->select = $value;

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
        if (null !== $v = $this->indexName) {
            $payload['IndexName'] = $v;
        }
        if (null !== $v = $this->select) {
            if (!Select::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "Select" for "%s". The value "%s" is not a valid "Select".', __CLASS__, $v));
            }
            $payload['Select'] = $v;
        }
        if (null !== $v = $this->attributesToGet) {
            $index = -1;
            $payload['AttributesToGet'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AttributesToGet'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->consistentRead) {
            $payload['ConsistentRead'] = (bool) $v;
        }
        if (null !== $v = $this->keyConditions) {
            if (empty($v)) {
                $payload['KeyConditions'] = new \stdClass();
            } else {
                $payload['KeyConditions'] = [];
                foreach ($v as $name => $mv) {
                    $payload['KeyConditions'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->queryFilter) {
            if (empty($v)) {
                $payload['QueryFilter'] = new \stdClass();
            } else {
                $payload['QueryFilter'] = [];
                foreach ($v as $name => $mv) {
                    $payload['QueryFilter'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->conditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
        }
        if (null !== $v = $this->scanIndexForward) {
            $payload['ScanIndexForward'] = (bool) $v;
        }
        if (null !== $v = $this->exclusiveStartKey) {
            if (empty($v)) {
                $payload['ExclusiveStartKey'] = new \stdClass();
            } else {
                $payload['ExclusiveStartKey'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ExclusiveStartKey'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->projectionExpression) {
            $payload['ProjectionExpression'] = $v;
        }
        if (null !== $v = $this->filterExpression) {
            $payload['FilterExpression'] = $v;
        }
        if (null !== $v = $this->keyConditionExpression) {
            $payload['KeyConditionExpression'] = $v;
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

        return $payload;
    }
}
