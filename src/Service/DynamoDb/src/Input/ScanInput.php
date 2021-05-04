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
 * Represents the input of a `Scan` operation.
 */
final class ScanInput extends Input
{
    /**
     * The name of the table containing the requested items; or, if you provide `IndexName`, the name of the table to which
     * that index belongs.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * The name of a secondary index to scan. This index can be any local secondary index or global secondary index. Note
     * that if you use the `IndexName` parameter, you must also provide `TableName`.
     *
     * @var string|null
     */
    private $indexName;

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
     * operation to continue the operation. For more information, see Working with Queries in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/QueryAndScan.html
     *
     * @var int|null
     */
    private $limit;

    /**
     * The attributes to be returned in the result. You can retrieve all item attributes, specific item attributes, the
     * count of matching items, or in the case of an index, some or all of the attributes projected into the index.
     *
     * @var null|Select::*
     */
    private $select;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see ScanFilter in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ScanFilter.html
     *
     * @var array<string, Condition>|null
     */
    private $scanFilter;

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
     * For a parallel `Scan` request, `TotalSegments` represents the total number of segments into which the `Scan`
     * operation will be divided. The value of `TotalSegments` corresponds to the number of application workers that will
     * perform the parallel scan. For example, if you want to use four application threads to scan a table or an index,
     * specify a `TotalSegments` value of 4.
     *
     * @var int|null
     */
    private $totalSegments;

    /**
     * For a parallel `Scan` request, `Segment` identifies an individual segment to be scanned by an application worker.
     *
     * @var int|null
     */
    private $segment;

    /**
     * A string that identifies one or more attributes to retrieve from the specified table or index. These attributes can
     * include scalars, sets, or elements of a JSON document. The attributes in the expression must be separated by commas.
     *
     * @var string|null
     */
    private $projectionExpression;

    /**
     * A string that contains conditions that DynamoDB applies after the `Scan` operation, but before the data is returned
     * to you. Items that do not satisfy the `FilterExpression` criteria are not returned.
     *
     * @var string|null
     */
    private $filterExpression;

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
     * A Boolean value that determines the read consistency model during the scan:.
     *
     * @var bool|null
     */
    private $consistentRead;

    /**
     * @param array{
     *   TableName?: string,
     *   IndexName?: string,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   Select?: Select::*,
     *   ScanFilter?: array<string, Condition>,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ExclusiveStartKey?: array<string, AttributeValue>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   TotalSegments?: int,
     *   Segment?: int,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   ConsistentRead?: bool,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->tableName = $input['TableName'] ?? null;
        $this->indexName = $input['IndexName'] ?? null;
        $this->attributesToGet = $input['AttributesToGet'] ?? null;
        $this->limit = $input['Limit'] ?? null;
        $this->select = $input['Select'] ?? null;

        if (isset($input['ScanFilter'])) {
            $this->scanFilter = [];
            foreach ($input['ScanFilter'] as $key => $item) {
                $this->scanFilter[$key] = Condition::create($item);
            }
        }
        $this->conditionalOperator = $input['ConditionalOperator'] ?? null;

        if (isset($input['ExclusiveStartKey'])) {
            $this->exclusiveStartKey = [];
            foreach ($input['ExclusiveStartKey'] as $key => $item) {
                $this->exclusiveStartKey[$key] = AttributeValue::create($item);
            }
        }
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->totalSegments = $input['TotalSegments'] ?? null;
        $this->segment = $input['Segment'] ?? null;
        $this->projectionExpression = $input['ProjectionExpression'] ?? null;
        $this->filterExpression = $input['FilterExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;

        if (isset($input['ExpressionAttributeValues'])) {
            $this->expressionAttributeValues = [];
            foreach ($input['ExpressionAttributeValues'] as $key => $item) {
                $this->expressionAttributeValues[$key] = AttributeValue::create($item);
            }
        }
        $this->consistentRead = $input['ConsistentRead'] ?? null;
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

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getProjectionExpression(): ?string
    {
        return $this->projectionExpression;
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->returnConsumedCapacity;
    }

    /**
     * @return array<string, Condition>
     */
    public function getScanFilter(): array
    {
        return $this->scanFilter ?? [];
    }

    public function getSegment(): ?int
    {
        return $this->segment;
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

    public function getTotalSegments(): ?int
    {
        return $this->totalSegments;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.Scan',
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
     * @param ReturnConsumedCapacity::*|null $value
     */
    public function setReturnConsumedCapacity(?string $value): self
    {
        $this->returnConsumedCapacity = $value;

        return $this;
    }

    /**
     * @param array<string, Condition> $value
     */
    public function setScanFilter(array $value): self
    {
        $this->scanFilter = $value;

        return $this;
    }

    public function setSegment(?int $value): self
    {
        $this->segment = $value;

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

    public function setTotalSegments(?int $value): self
    {
        $this->totalSegments = $value;

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
        if (null !== $v = $this->select) {
            if (!Select::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Select" for "%s". The value "%s" is not a valid "Select".', __CLASS__, $v));
            }
            $payload['Select'] = $v;
        }
        if (null !== $v = $this->scanFilter) {
            if (empty($v)) {
                $payload['ScanFilter'] = new \stdClass();
            } else {
                $payload['ScanFilter'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ScanFilter'][$name] = $mv->requestBody();
                }
            }
        }
        if (null !== $v = $this->conditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
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
        if (null !== $v = $this->totalSegments) {
            $payload['TotalSegments'] = $v;
        }
        if (null !== $v = $this->segment) {
            $payload['Segment'] = $v;
        }
        if (null !== $v = $this->projectionExpression) {
            $payload['ProjectionExpression'] = $v;
        }
        if (null !== $v = $this->filterExpression) {
            $payload['FilterExpression'] = $v;
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
        if (null !== $v = $this->consistentRead) {
            $payload['ConsistentRead'] = (bool) $v;
        }

        return $payload;
    }
}
