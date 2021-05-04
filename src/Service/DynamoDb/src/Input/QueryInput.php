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
     * The name of the table containing the requested items.
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
     * @var null|Select::*
     */
    private $select;

    /**
     * This is a legacy parameter. Use `ProjectionExpression` instead. For more information, see AttributesToGet in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.AttributesToGet.html
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
     * operation to continue the operation. For more information, see Query and Scan in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/QueryAndScan.html
     *
     * @var int|null
     */
    private $limit;

    /**
     * Determines the read consistency model: If set to `true`, then the operation uses strongly consistent reads;
     * otherwise, the operation uses eventually consistent reads.
     *
     * @var bool|null
     */
    private $consistentRead;

    /**
     * This is a legacy parameter. Use `KeyConditionExpression` instead. For more information, see KeyConditions in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.KeyConditions.html
     *
     * @var array<string, Condition>|null
     */
    private $keyConditions;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see QueryFilter in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.QueryFilter.html
     *
     * @var array<string, Condition>|null
     */
    private $queryFilter;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see ConditionalOperator in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var null|ConditionalOperator::*
     */
    private $conditionalOperator;

    /**
     * Specifies the order for index traversal: If `true` (default), the traversal is performed in ascending order; if
     * `false`, the traversal is performed in descending order.
     *
     * @var bool|null
     */
    private $scanIndexForward;

    /**
     * The primary key of the first item that this operation will evaluate. Use the value that was returned for
     * `LastEvaluatedKey` in the previous operation.
     *
     * @var array<string, AttributeValue>|null
     */
    private $exclusiveStartKey;

    /**
     * @var null|ReturnConsumedCapacity::*
     */
    private $returnConsumedCapacity;

    /**
     * A string that identifies one or more attributes to retrieve from the table. These attributes can include scalars,
     * sets, or elements of a JSON document. The attributes in the expression must be separated by commas.
     *
     * @var string|null
     */
    private $projectionExpression;

    /**
     * A string that contains conditions that DynamoDB applies after the `Query` operation, but before the data is returned
     * to you. Items that do not satisfy the `FilterExpression` criteria are not returned.
     *
     * @var string|null
     */
    private $filterExpression;

    /**
     * The condition that specifies the key values for items to be retrieved by the `Query` action.
     *
     * @var string|null
     */
    private $keyConditionExpression;

    /**
     * One or more substitution tokens for attribute names in an expression. The following are some use cases for using
     * `ExpressionAttributeNames`:.
     *
     * @var array<string, string>|null
     */
    private $expressionAttributeNames;

    /**
     * One or more values that can be substituted in an expression.
     *
     * @var array<string, AttributeValue>|null
     */
    private $expressionAttributeValues;

    /**
     * @param array{
     *   TableName?: string,
     *   IndexName?: string,
     *   Select?: Select::*,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   ConsistentRead?: bool,
     *   KeyConditions?: array<string, Condition>,
     *   QueryFilter?: array<string, Condition>,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ScanIndexForward?: bool,
     *   ExclusiveStartKey?: array<string, AttributeValue>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   KeyConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   @region?: string,
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
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null !== $v = $this->indexName) {
            $payload['IndexName'] = $v;
        }
        if (null !== $v = $this->select) {
            if (!Select::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Select" for "%s". The value "%s" is not a valid "Select".', __CLASS__, $v));
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
                throw new InvalidArgument(sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
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
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
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
