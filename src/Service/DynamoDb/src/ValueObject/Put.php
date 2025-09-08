<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ReturnValuesOnConditionCheckFailure;

/**
 * Represents a request to perform a `PutItem` operation.
 */
final class Put
{
    /**
     * A map of attribute name to attribute values, representing the primary key of the item to be written by `PutItem`. All
     * of the table's primary key attributes must be specified, and their data types must match those of the table's key
     * schema. If any attributes are present in the item that are part of an index key schema for the table, their types
     * must match the index key schema.
     *
     * @var array<string, AttributeValue>
     */
    private $item;

    /**
     * Name of the table in which to write the item. You can also provide the Amazon Resource Name (ARN) of the table in
     * this parameter.
     *
     * @var string
     */
    private $tableName;

    /**
     * A condition that must be satisfied in order for a conditional update to succeed.
     *
     * @var string|null
     */
    private $conditionExpression;

    /**
     * One or more substitution tokens for attribute names in an expression.
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
     * Use `ReturnValuesOnConditionCheckFailure` to get the item attributes if the `Put` condition fails. For
     * `ReturnValuesOnConditionCheckFailure`, the valid values are: NONE and ALL_OLD.
     *
     * @var ReturnValuesOnConditionCheckFailure::*|null
     */
    private $returnValuesOnConditionCheckFailure;

    /**
     * @param array{
     *   Item: array<string, AttributeValue|array>,
     *   TableName: string,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->item = isset($input['Item']) ? array_map([AttributeValue::class, 'create'], $input['Item']) : $this->throwException(new InvalidArgument('Missing required field "Item".'));
        $this->tableName = $input['TableName'] ?? $this->throwException(new InvalidArgument('Missing required field "TableName".'));
        $this->conditionExpression = $input['ConditionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;
        $this->expressionAttributeValues = isset($input['ExpressionAttributeValues']) ? array_map([AttributeValue::class, 'create'], $input['ExpressionAttributeValues']) : null;
        $this->returnValuesOnConditionCheckFailure = $input['ReturnValuesOnConditionCheckFailure'] ?? null;
    }

    /**
     * @param array{
     *   Item: array<string, AttributeValue|array>,
     *   TableName: string,
     *   ConditionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   ExpressionAttributeValues?: array<string, AttributeValue|array>|null,
     *   ReturnValuesOnConditionCheckFailure?: ReturnValuesOnConditionCheckFailure::*|null,
     * }|Put $input
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
        return $this->item;
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
        $v = $this->item;

        if (empty($v)) {
            $payload['Item'] = new \stdClass();
        } else {
            $payload['Item'] = [];
            foreach ($v as $name => $mv) {
                $payload['Item'][$name] = $mv->requestBody();
            }
        }
        $v = $this->tableName;
        $payload['TableName'] = $v;
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
