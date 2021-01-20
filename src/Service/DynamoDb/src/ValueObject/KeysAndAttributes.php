<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a set of primary keys and, for each key, the attributes to retrieve from the table.
 * For each primary key, you must provide *all* of the key attributes. For example, with a simple primary key, you only
 * need to provide the partition key. For a composite primary key, you must provide *both* the partition key and the
 * sort key.
 */
final class KeysAndAttributes
{
    /**
     * The primary key attribute values that define the items and the attributes associated with the items.
     */
    private $keys;

    /**
     * This is a legacy parameter. Use `ProjectionExpression` instead. For more information, see Legacy Conditional
     * Parameters in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.html
     */
    private $attributesToGet;

    /**
     * The consistency of a read operation. If set to `true`, then a strongly consistent read is used; otherwise, an
     * eventually consistent read is used.
     */
    private $consistentRead;

    /**
     * A string that identifies one or more attributes to retrieve from the table. These attributes can include scalars,
     * sets, or elements of a JSON document. The attributes in the `ProjectionExpression` must be separated by commas.
     */
    private $projectionExpression;

    /**
     * One or more substitution tokens for attribute names in an expression. The following are some use cases for using
     * `ExpressionAttributeNames`:.
     */
    private $expressionAttributeNames;

    /**
     * @param array{
     *   Keys: array[],
     *   AttributesToGet?: null|string[],
     *   ConsistentRead?: null|bool,
     *   ProjectionExpression?: null|string,
     *   ExpressionAttributeNames?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->keys = $input['Keys'] ?? null;
        $this->attributesToGet = $input['AttributesToGet'] ?? null;
        $this->consistentRead = $input['ConsistentRead'] ?? null;
        $this->projectionExpression = $input['ProjectionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;
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

    public function getConsistentRead(): ?bool
    {
        return $this->consistentRead;
    }

    /**
     * @return array<string, string>
     */
    public function getExpressionAttributeNames(): array
    {
        return $this->expressionAttributeNames ?? [];
    }

    /**
     * @return array<string, AttributeValue>[]
     */
    public function getKeys(): array
    {
        return $this->keys ?? [];
    }

    public function getProjectionExpression(): ?string
    {
        return $this->projectionExpression;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->keys) {
            throw new InvalidArgument(sprintf('Missing parameter "Keys" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['Keys'] = [];
        foreach ($v as $listValue) {
            ++$index;

            if (empty($listValue)) {
                $payload['Keys'][$index] = new \stdClass();
            } else {
                $payload['Keys'][$index] = [];
                foreach ($listValue as $name => $mv) {
                    $payload['Keys'][$index][$name] = $mv->requestBody();
                }
            }
        }

        if (null !== $v = $this->attributesToGet) {
            $index = -1;
            $payload['AttributesToGet'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['AttributesToGet'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->consistentRead) {
            $payload['ConsistentRead'] = (bool) $v;
        }
        if (null !== $v = $this->projectionExpression) {
            $payload['ProjectionExpression'] = $v;
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

        return $payload;
    }
}
