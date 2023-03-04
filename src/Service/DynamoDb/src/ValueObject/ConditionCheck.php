<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ReturnValuesOnConditionCheckFailure;

/**
 * A request to perform a check item operation.
 */
final class ConditionCheck
{
    /**
     * The primary key of the item to be checked. Each element consists of an attribute name and a value for that attribute.
     */
    private $key;

    /**
     * Name of the table for the check item request.
     */
    private $tableName;

    /**
     * A condition that must be satisfied in order for a conditional update to succeed. For more information, see Condition
     * expressions in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.ConditionExpressions.html
     */
    private $conditionExpression;

    /**
     * One or more substitution tokens for attribute names in an expression. For more information, see Expression attribute
     * names in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.ExpressionAttributeNames.html
     */
    private $expressionAttributeNames;

    /**
     * One or more values that can be substituted in an expression. For more information, see Condition expressions in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.ConditionExpressions.html
     */
    private $expressionAttributeValues;

    /**
     * Use `ReturnValuesOnConditionCheckFailure` to get the item attributes if the `ConditionCheck` condition fails. For
     * `ReturnValuesOnConditionCheckFailure`, the valid values are: NONE and ALL_OLD.
     */
    private $returnValuesOnConditionCheckFailure;

    /**
     * @param array{
     *   Key: array<string, AttributeValue>,
     *   TableName: string,
     *   ConditionExpression: string,
     *   ExpressionAttributeNames?: null|array<string, string>,
     *   ExpressionAttributeValues?: null|array<string, AttributeValue>,
     *   ReturnValuesOnConditionCheckFailure?: null|ReturnValuesOnConditionCheckFailure::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = isset($input['Key']) ? array_map([AttributeValue::class, 'create'], $input['Key']) : null;
        $this->tableName = $input['TableName'] ?? null;
        $this->conditionExpression = $input['ConditionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;
        $this->expressionAttributeValues = isset($input['ExpressionAttributeValues']) ? array_map([AttributeValue::class, 'create'], $input['ExpressionAttributeValues']) : null;
        $this->returnValuesOnConditionCheckFailure = $input['ReturnValuesOnConditionCheckFailure'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConditionExpression(): string
    {
        return $this->conditionExpression;
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
     * @return ReturnValuesOnConditionCheckFailure::*|null
     */
    public function getReturnValuesOnConditionCheckFailure(): ?string
    {
        return $this->returnValuesOnConditionCheckFailure;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['Key'] = new \stdClass();
        } else {
            $payload['Key'] = [];
            foreach ($v as $name => $mv) {
                $payload['Key'][$name] = $mv->requestBody();
            }
        }
        if (null === $v = $this->tableName) {
            throw new InvalidArgument(sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->conditionExpression) {
            throw new InvalidArgument(sprintf('Missing parameter "ConditionExpression" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ConditionExpression'] = $v;
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
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnValuesOnConditionCheckFailure" for "%s". The value "%s" is not a valid "ReturnValuesOnConditionCheckFailure".', __CLASS__, $v));
            }
            $payload['ReturnValuesOnConditionCheckFailure'] = $v;
        }

        return $payload;
    }
}
