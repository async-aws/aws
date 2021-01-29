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
use AsyncAws\DynamoDb\ValueObject\AttributeValue;
use AsyncAws\DynamoDb\ValueObject\ExpectedAttributeValue;

/**
 * Represents the input of a `PutItem` operation.
 */
final class PutItemInput extends Input
{
    /**
     * The name of the table to contain the item.
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
     * @required
     *
     * @var array<string, AttributeValue>|null
     */
    private $item;

    /**
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see Expected in the *Amazon
     * DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.Expected.html
     *
     * @var array<string, ExpectedAttributeValue>|null
     */
    private $expected;

    /**
     * Use `ReturnValues` if you want to get the item attributes as they appeared before they were updated with the
     * `PutItem` request. For `PutItem`, the valid values are:.
     *
     * @var null|ReturnValue::*
     */
    private $returnValues;

    /**
     * @var null|ReturnConsumedCapacity::*
     */
    private $returnConsumedCapacity;

    /**
     * Determines whether item collection metrics are returned. If set to `SIZE`, the response includes statistics about
     * item collections, if any, that were modified during the operation are returned in the response. If set to `NONE` (the
     * default), no statistics are returned.
     *
     * @var null|ReturnItemCollectionMetrics::*
     */
    private $returnItemCollectionMetrics;

    /**
     * This is a legacy parameter. Use `ConditionExpression` instead. For more information, see ConditionalOperator in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.ConditionalOperator.html
     *
     * @var null|ConditionalOperator::*
     */
    private $conditionalOperator;

    /**
     * A condition that must be satisfied in order for a conditional `PutItem` operation to succeed.
     *
     * @var string|null
     */
    private $conditionExpression;

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
     *   Item?: array<string, AttributeValue>,
     *   Expected?: array<string, ExpectedAttributeValue>,
     *   ReturnValues?: ReturnValue::*,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*,
     *   ConditionalOperator?: ConditionalOperator::*,
     *   ConditionExpression?: string,
     *   ExpressionAttributeNames?: array<string, string>,
     *   ExpressionAttributeValues?: array<string, AttributeValue>,
     *   @region?: string,
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
        parent::__construct($input);
    }

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
        if (null === $v = $this->item) {
            throw new InvalidArgument(sprintf('Missing parameter "Item" for "%s". The value cannot be null.', __CLASS__));
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
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnValues" for "%s". The value "%s" is not a valid "ReturnValue".', __CLASS__, $v));
            }
            $payload['ReturnValues'] = $v;
        }
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->returnItemCollectionMetrics) {
            if (!ReturnItemCollectionMetrics::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnItemCollectionMetrics" for "%s". The value "%s" is not a valid "ReturnItemCollectionMetrics".', __CLASS__, $v));
            }
            $payload['ReturnItemCollectionMetrics'] = $v;
        }
        if (null !== $v = $this->conditionalOperator) {
            if (!ConditionalOperator::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "ConditionalOperator" for "%s". The value "%s" is not a valid "ConditionalOperator".', __CLASS__, $v));
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

        return $payload;
    }
}
