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

final class ScanInput implements Input
{
    /**
     * The name of the table containing the requested items; or, if you provide `IndexName`, the name of the table to which
     * that index belongs.
     *
     * @required
     *
     * @var string|null
     */
    private $TableName;

    /**
     * The name of a secondary index to scan. This index can be any local secondary index or global secondary index. Note
     * that if you use the `IndexName` parameter, you must also provide `TableName`.
     *
     * @var string|null
     */
    private $IndexName;

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
     * operation to continue the operation. For more information, see Working with Queries in the *Amazon DynamoDB Developer
     * Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/QueryAndScan.html
     *
     * @var int|null
     */
    private $Limit;

    /**
     * The attributes to be returned in the result. You can retrieve all item attributes, specific item attributes, the
     * count of matching items, or in the case of an index, some or all of the attributes projected into the index.
     *
     * @var null|Select::*
     */
    private $Select;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see ScanFilter in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ScanFilter.html
     *
     * @var Condition[]
     */
    private $ScanFilter;

    /**
     * This is a legacy parameter. Use `FilterExpression` instead. For more information, see ConditionalOperator in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var null|ConditionalOperator::*
     */
    private $ConditionalOperator;

    /**
     * The primary key of the first item that this operation will evaluate. Use the value that was returned for
     * `LastEvaluatedKey` in the previous operation.
     *
     * @var AttributeValue[]
     */
    private $ExclusiveStartKey;

    /**
     * @var null|ReturnConsumedCapacity::*
     */
    private $ReturnConsumedCapacity;

    /**
     * For a parallel `Scan` request, `TotalSegments` represents the total number of segments into which the `Scan`
     * operation will be divided. The value of `TotalSegments` corresponds to the number of application workers that will
     * perform the parallel scan. For example, if you want to use four application threads to scan a table or an index,
     * specify a `TotalSegments` value of 4.
     *
     * @var int|null
     */
    private $TotalSegments;

    /**
     * For a parallel `Scan` request, `Segment` identifies an individual segment to be scanned by an application worker.
     *
     * @var int|null
     */
    private $Segment;

    /**
     * A string that identifies one or more attributes to retrieve from the specified table or index. These attributes can
     * include scalars, sets, or elements of a JSON document. The attributes in the expression must be separated by commas.
     *
     * @var string|null
     */
    private $ProjectionExpression;

    /**
     * A string that contains conditions that DynamoDB applies after the `Scan` operation, but before the data is returned
     * to you. Items that do not satisfy the `FilterExpression` criteria are not returned.
     *
     * @var string|null
     */
    private $FilterExpression;

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
     * A Boolean value that determines the read consistency model during the scan:.
     *
     * @var bool|null
     */
    private $ConsistentRead;

    /**
     * @param array{
     *   TableName?: string,
     *   IndexName?: string,
     *   AttributesToGet?: string[],
     *   Limit?: int,
     *   Select?: \AsyncAws\DynamoDb\Enum\Select::*,
     *   ScanFilter?: \AsyncAws\DynamoDb\ValueObject\Condition[],
     *   ConditionalOperator?: \AsyncAws\DynamoDb\Enum\ConditionalOperator::*,
     *   ExclusiveStartKey?: \AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     *   ReturnConsumedCapacity?: \AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity::*,
     *   TotalSegments?: int,
     *   Segment?: int,
     *   ProjectionExpression?: string,
     *   FilterExpression?: string,
     *   ExpressionAttributeNames?: string[],
     *   ExpressionAttributeValues?: \AsyncAws\DynamoDb\ValueObject\AttributeValue[],
     *   ConsistentRead?: bool,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->TableName = $input['TableName'] ?? null;
        $this->IndexName = $input['IndexName'] ?? null;
        $this->AttributesToGet = $input['AttributesToGet'] ?? [];
        $this->Limit = $input['Limit'] ?? null;
        $this->Select = $input['Select'] ?? null;

        $this->ScanFilter = [];
        foreach ($input['ScanFilter'] ?? [] as $key => $item) {
            $this->ScanFilter[$key] = Condition::create($item);
        }
        $this->ConditionalOperator = $input['ConditionalOperator'] ?? null;

        $this->ExclusiveStartKey = [];
        foreach ($input['ExclusiveStartKey'] ?? [] as $key => $item) {
            $this->ExclusiveStartKey[$key] = AttributeValue::create($item);
        }
        $this->ReturnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->TotalSegments = $input['TotalSegments'] ?? null;
        $this->Segment = $input['Segment'] ?? null;
        $this->ProjectionExpression = $input['ProjectionExpression'] ?? null;
        $this->FilterExpression = $input['FilterExpression'] ?? null;
        $this->ExpressionAttributeNames = $input['ExpressionAttributeNames'] ?? [];

        $this->ExpressionAttributeValues = [];
        foreach ($input['ExpressionAttributeValues'] ?? [] as $key => $item) {
            $this->ExpressionAttributeValues[$key] = AttributeValue::create($item);
        }
        $this->ConsistentRead = $input['ConsistentRead'] ?? null;
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

    public function getLimit(): ?int
    {
        return $this->Limit;
    }

    public function getProjectionExpression(): ?string
    {
        return $this->ProjectionExpression;
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->ReturnConsumedCapacity;
    }

    /**
     * @return Condition[]
     */
    public function getScanFilter(): array
    {
        return $this->ScanFilter;
    }

    public function getSegment(): ?int
    {
        return $this->Segment;
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

    public function getTotalSegments(): ?int
    {
        return $this->TotalSegments;
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
     * @param ReturnConsumedCapacity::*|null $value
     */
    public function setReturnConsumedCapacity(?string $value): self
    {
        $this->ReturnConsumedCapacity = $value;

        return $this;
    }

    /**
     * @param Condition[] $value
     */
    public function setScanFilter(array $value): self
    {
        $this->ScanFilter = $value;

        return $this;
    }

    public function setSegment(?int $value): self
    {
        $this->Segment = $value;

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

    public function setTotalSegments(?int $value): self
    {
        $this->TotalSegments = $value;

        return $this;
    }

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

        $index = -1;
        foreach ($this->AttributesToGet as $listValue) {
            ++$index;
            $payload['AttributesToGet'][$index] = $listValue;
        }

        if (null !== $v = $this->Limit) {
            $payload['Limit'] = $v;
        }
        if (null !== $v = $this->Select) {
            if (!Select::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "Select" for "%s". The value "%s" is not a valid "Select".', __CLASS__, $v));
            }
            $payload['Select'] = $v;
        }

        foreach ($this->ScanFilter as $name => $v) {
            $payload['ScanFilter'][$name] = $v->requestBody();
        }
        if (null !== $v = $this->ConditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
            }
            $payload['ConditionalOperator'] = $v;
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
        if (null !== $v = $this->TotalSegments) {
            $payload['TotalSegments'] = $v;
        }
        if (null !== $v = $this->Segment) {
            $payload['Segment'] = $v;
        }
        if (null !== $v = $this->ProjectionExpression) {
            $payload['ProjectionExpression'] = $v;
        }
        if (null !== $v = $this->FilterExpression) {
            $payload['FilterExpression'] = $v;
        }

        foreach ($this->ExpressionAttributeNames as $name => $v) {
            $payload['ExpressionAttributeNames'][$name] = $v;
        }

        foreach ($this->ExpressionAttributeValues as $name => $v) {
            $payload['ExpressionAttributeValues'][$name] = $v->requestBody();
        }
        if (null !== $v = $this->ConsistentRead) {
            $payload['ConsistentRead'] = (bool) $v;
        }

        return $payload;
    }
}
