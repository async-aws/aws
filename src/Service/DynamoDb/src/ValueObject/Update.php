<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\DynamoDb\Enum\ReturnValuesOnConditionCheckFailure;

/**
 * Represents a request to perform an `UpdateItem` operation.
 */
final class Update
{
    /**
     * The primary key of the item to be updated. Each element consists of an attribute name and a value for that attribute.
     *
     * @var array<string, AttributeValue>
     */
    private $key;

    /**
     * An expression that defines one or more attributes to be updated, the action to be performed on them, and new value(s)
     * for them.
     *
     * @var string
     */
    private $updateExpression;

    /**
     * Name of the table for the `UpdateItem` request.
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
     * Use `ReturnValuesOnConditionCheckFailure` to get the item attributes if the `Update` condition fails. For
     * `ReturnValuesOnConditionCheckFailure`, the valid values are: NONE, ALL_OLD, UPDATED_OLD, ALL_NEW, UPDATED_NEW.
     *
     * @var ReturnValuesOnConditionCheckFailure::*|null
     */
    private $returnValuesOnConditionCheckFailure;

    /**
     * @param array{
     *   Key: array<string, AttributeValue|array>,
     *   UpdateExpression: string,
     *   TableName: string,
     *   ConditionExpression?: null|string,
     *   ExpressionAttributeNames?: null|array<string, string>,
     *   ExpressionAttributeValues?: null|array<string, AttributeValue|array>,
     *   ReturnValuesOnConditionCheckFailure?: null|ReturnValuesOnConditionCheckFailure::*,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = isset($input['Key']) ? array_map([AttributeValue::class, 'create'], $input['Key']) : $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->updateExpression = $input['UpdateExpression'] ?? $this->throwException(new InvalidArgument('Missing required field "UpdateExpression".'));
        $this->tableName = $input['TableName'] ?? $this->throwException(new InvalidArgument('Missing required field "TableName".'));
        $this->conditionExpression = $input['ConditionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;
        $this->expressionAttributeValues = isset($input['ExpressionAttributeValues']) ? array_map([AttributeValue::class, 'create'], $input['ExpressionAttributeValues']) : null;
        $this->returnValuesOnConditionCheckFailure = $input['ReturnValuesOnConditionCheckFailure'] ?? null;
    }

    /**
     * @param array{
     *   Key: array<string, AttributeValue|array>,
     *   UpdateExpression: string,
     *   TableName: string,
     *   ConditionExpression?: null|string,
     *   ExpressionAttributeNames?: null|array<string, string>,
     *   ExpressionAttributeValues?: null|array<string, AttributeValue|array>,
     *   ReturnValuesOnConditionCheckFailure?: null|ReturnValuesOnConditionCheckFailure::*,
     * }|Update $input
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
    public function getKey(): array
    {
        return $this->key;
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

    public function getUpdateExpression(): string
    {
        return $this->updateExpression;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->key;

        if (empty($v)) {
            $payload['Key'] = new \stdClass();
        } else {
            $payload['Key'] = [];
            foreach ($v as $name => $mv) {
                $payload['Key'][$name] = $mv->requestBody();
            }
        }
        $v = $this->updateExpression;
        $payload['UpdateExpression'] = $v;
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
                throw new InvalidArgument(sprintf('Invalid parameter "ReturnValuesOnConditionCheckFailure" for "%s". The value "%s" is not a valid "ReturnValuesOnConditionCheckFailure".', __CLASS__, $v));
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
