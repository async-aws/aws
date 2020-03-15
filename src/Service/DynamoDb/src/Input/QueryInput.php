<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ConditionalOperator;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\Select;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\Condition;

class QueryInput
{
    /**
     * The name of the table containing the requested items.
     *
     * @required
     *
     * @var string|null
     */
    private $TableName;

    /**
     * The name of an index to query. This index can be any local secondary index or global secondary index on the table.
     * Note that if you use the `IndexName` parameter, you must also provide `TableName.`.
     *
     * @var string|null
     */
    private $IndexName;

    /**
     * The attributes to be returned in the result. You can retrieve all item attributes, specific item attributes, the
     * count of matching items, or in the case of an index, some or all of the attributes projected into the index.
     *
     * @var Select::*|null
     */
    private $Select;

    /**
     * This is a legacy parameter. Use `ProjectionExpression` instead. For more information, see AttributesToGet in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.AttributesToGet.html
     *
     * @var string[]
     */
    private $AttributesToGet;

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
    private $Limit;

    /**
     * Determines the read consistency model: If set to `true`, then the operation uses strongly consistent reads;
     * otherwise, the operation uses eventually consistent reads.
     *
     * @var bool|null
     */
    private $ConsistentRead;

    /**
     * This is a legacy parameter. Use `KeyConditionExpression` instead. For more information, see KeyConditions in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.KeyConditions.html
     *
     * @var Condition[]
     */
    private $KeyConditions;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see QueryFilter in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.QueryFilter.html
     *
     * @var Condition[]
     */
    private $QueryFilter;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see ConditionalOperator in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var ConditionalOperator::*|null
     */
    private $ConditionalOperator;

    /**
     * Specifies the order for index traversal: If `true` (default), the traversal is performed in ascending order; if
     * `false`, the traversal is performed in descending order.
     *
     * @var bool|null
     */
    private $ScanIndexForward;

    /**
     * The primary key of the first item that this operation will evaluate. Use the value that was returned for
     * `LastEvaluatedKey` in the previous operation.
     *
     * @var AttributeValue[]
     */
    private $ExclusiveStartKey;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $ReturnConsumedCapacity;

    /**
     * A string that identifies one or more attributes to retrieve from the table. These attributes can include scalars,
     * sets, or elements of a JSON document. The attributes in the expression must be separated by commas.
     *
     * @var string|null
     */
    private $ProjectionExpression;

    /**
     * A string that contains conditions that DynamoDB applies after the `Query` operation, but before the data is returned
     * to you. Items that do not satisfy the `FilterExpression` criteria are not returned.
     *
     * @var string|null
     */
    private $FilterExpression;

    /**
     * The condition that specifies the key values for items to be retrieved by the `Query` action.
     *
     * @var string|null
     */
    private $KeyConditionExpression;

    /**
     * One or more substitution tokens for attribute names in an expression. The following are some use cases for using
     * `ExpressionAttributeNames`:.
     *
     * @var string[]
     */
    private $ExpressionAttributeNames;

    /**
     * One or more values that can be substituted in an expression.
     *
     * @var AttributeValue[]
     */
    private $ExpressionAttributeValues;

    /**
     * @param array{
     *   TableName?: string,
     *   IndexName?: string,
     *   Select?: \AsyncAws\DynamoDb\Enum\Select::*,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   ConsistentRead?: bool,
     *   KeyConditions?: \AsyncAws\DynamoDb\ValueObject\Condition[],
     *   QueryFilter?: \AsyncAws\DynamoDb\ValueObject\Condition[],
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ScanIndexForward?: bool,
     *   ExclusiveStartKey?: \AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   KeyConditionExpression?: string,
     *   ExpressionAttributeNames?: string[],
     *   ExpressionAttributeValues?: \AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->TableName = $input['TableName'] ?? null;
        $this->IndexName = $input['IndexName'] ?? null;
        $this->Select = $input['Select'] ?? null;
        $this->AttributesToGet = $input['AttributesToGet'] ?? [];
        $this->Limit = $input['Limit'] ?? null;
        $this->ConsistentRead = $input['ConsistentRead'] ?? null;

        $this->KeyConditions = [];
        foreach ($input['KeyConditions'] ?? [] as $key => $item) {
            $this->KeyConditions[$key] = Condition::create($item);
        }

        $this->QueryFilter = [];
        foreach ($input['QueryFilter'] ?? [] as $key => $item) {
            $this->QueryFilter[$key] = Condition::create($item);
        }
        $this->ConditionalOperator = $input['ConditionalOperator'] ?? null;
        $this->ScanIndexForward = $input['ScanIndexForward'] ?? null;

        $this->ExclusiveStartKey = [];
        foreach ($input['ExclusiveStartKey'] ?? [] as $key => $item) {
            $this->ExclusiveStartKey[$key] = AttributeValue::create($item);
        }
        $this->ReturnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->ProjectionExpression = $input['ProjectionExpression'] ?? null;
        $this->FilterExpression = $input['FilterExpression'] ?? null;
        $this->KeyConditionExpression = $input['KeyConditionExpression'] ?? null;
        $this->ExpressionAttributeNames = $input['ExpressionAttributeNames'] ?? [];

        $this->ExpressionAttributeValues = [];
        foreach ($input['ExpressionAttributeValues'] ?? [] as $key => $item) {
            $this->ExpressionAttributeValues[$key] = AttributeValue::create($item);
        }
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
        return $this->AttributesToGet;
    }

    /**
     * @return ConditionalOperator::*|null
     */
    public function getConditionalOperator(): ?string
    {
        return $this->ConditionalOperator;
    }

    public function getConsistentRead(): ?bool
    {
        return $this->ConsistentRead;
    }

    /**
     * @return AttributeValue[]
     */
    public function getExclusiveStartKey(): array
    {
        return $this->ExclusiveStartKey;
    }

    /**
     * @return string[]
     */
    public function getExpressionAttributeNames(): array
    {
        return $this->ExpressionAttributeNames;
    }

    /**
     * @return AttributeValue[]
     */
    public function getExpressionAttributeValues(): array
    {
        return $this->ExpressionAttributeValues;
    }

    public function getFilterExpression(): ?string
    {
        return $this->FilterExpression;
    }

    public function getIndexName(): ?string
    {
        return $this->IndexName;
    }

    public function getKeyConditionExpression(): ?string
    {
        return $this->KeyConditionExpression;
    }

    /**
     * @return Condition[]
     */
    public function getKeyConditions(): array
    {
        return $this->KeyConditions;
    }

    public function getLimit(): ?int
    {
        return $this->Limit;
    }

    public function getProjectionExpression(): ?string
    {
        return $this->ProjectionExpression;
    }

    /**
     * @return Condition[]
     */
    public function getQueryFilter(): array
    {
        return $this->QueryFilter;
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->ReturnConsumedCapacity;
    }

    public function getScanIndexForward(): ?bool
    {
        return $this->ScanIndexForward;
    }

    /**
     * @return Select::*|null
     */
    public function getSelect(): ?string
    {
        return $this->Select;
    }

    public function getTableName(): ?string
    {
        return $this->TableName;
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param string[] $value
     */
    public function setAttributesToGet(array $value): self
    {
        $this->AttributesToGet = $value;

        return $this;
    }

    /**
     * @param ConditionalOperator::*|null $value
     */
    public function setConditionalOperator(?string $value): self
    {
        $this->ConditionalOperator = $value;

        return $this;
    }

    public function setConsistentRead(?bool $value): self
    {
        $this->ConsistentRead = $value;

        return $this;
    }

    /**
     * @param AttributeValue[] $value
     */
    public function setExclusiveStartKey(array $value): self
    {
        $this->ExclusiveStartKey = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setExpressionAttributeNames(array $value): self
    {
        $this->ExpressionAttributeNames = $value;

        return $this;
    }

    /**
     * @param AttributeValue[] $value
     */
    public function setExpressionAttributeValues(array $value): self
    {
        $this->ExpressionAttributeValues = $value;

        return $this;
    }

    public function setFilterExpression(?string $value): self
    {
        $this->FilterExpression = $value;

        return $this;
    }

    public function setIndexName(?string $value): self
    {
        $this->IndexName = $value;

        return $this;
    }

    public function setKeyConditionExpression(?string $value): self
    {
        $this->KeyConditionExpression = $value;

        return $this;
    }

    /**
     * @param Condition[] $value
     */
    public function setKeyConditions(array $value): self
    {
        $this->KeyConditions = $value;

        return $this;
    }

    public function setLimit(?int $value): self
    {
        $this->Limit = $value;

        return $this;
    }

    public function setProjectionExpression(?string $value): self
    {
        $this->ProjectionExpression = $value;

        return $this;
    }

    /**
     * @param Condition[] $value
     */
    public function setQueryFilter(array $value): self
    {
        $this->QueryFilter = $value;

        return $this;
    }

    /**
     * @param ReturnConsumedCapacity::*|null $value
     */
    public function setReturnConsumedCapacity(?string $value): self
    {
        $this->ReturnConsumedCapacity = $value;

        return $this;
    }

    public function setScanIndexForward(?bool $value): self
    {
        $this->ScanIndexForward = $value;

        return $this;
    }

    /**
     * @param Select::*|null $value
     */
    public function setSelect(?string $value): self
    {
        $this->Select = $value;

        return $this;
    }

    public function setTableName(?string $value): self
    {
        $this->TableName = $value;

        return $this;
    }

    /**
     * @internal
     */
    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->TableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null !== $v = $this->IndexName) {
            $payload['IndexName'] = $v;
        }
        if (null !== $v = $this->Select) {
            if (!Select::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Select" for "%s". The value "%s" is not a valid "Select".', __CLASS__, $v));
            }
            $payload['Select'] = $v;
        }

        $index = -1;
        foreach ($this->AttributesToGet as $listValue) {
            ++$index;
            $payload['AttributesToGet'][$index] = $listValue;
        }

        if (null !== $v = $this->Limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->ConsistentRead) {
            $payload['ConsistentRead'] = (bool) $v;
        }

        foreach ($this->KeyConditions as $name => $v) {
            $payload['KeyConditions'][$name] = $v->requestBody();
        }

        foreach ($this->QueryFilter as $name => $v) {
            $payload['QueryFilter'][$name] = $v->requestBody();
        }
        if (null !== $v = $this->ConditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
        }
        if (null !== $v = $this->ScanIndexForward) {
            $payload['ScanIndexForward'] = (bool) $v;
        }

        foreach ($this->ExclusiveStartKey as $name => $v) {
            $payload['ExclusiveStartKey'][$name] = $v->requestBody();
        }
        if (null !== $v = $this->ReturnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->ProjectionExpression) {
            $payload['ProjectionExpression'] = $v;
        }
        if (null !== $v = $this->FilterExpression) {
            $payload['FilterExpression'] = $v;
        }
        if (null !== $v = $this->KeyConditionExpression) {
            $payload['KeyConditionExpression'] = $v;
        }

        foreach ($this->ExpressionAttributeNames as $name => $v) {
            $payload['ExpressionAttributeNames'][$name] = $v;
        }

        foreach ($this->ExpressionAttributeValues as $name => $v) {
            $payload['ExpressionAttributeValues'][$name] = $v->requestBody();
        }

        return $payload;
    }
}
