<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a set of primary keys and, for each key, the attributes to retrieve from the table.
 *
 * For each primary key, you must provide *all* of the key attributes. For example, with a simple primary key, you only
 * need to provide the partition key. For a composite primary key, you must provide *both* the partition key and the
 * sort key.
 */
final class KeysAndAttributes
{
    /**
     * The primary key attribute values that define the items and the attributes associated with the items.
     *
     * @var array<string, AttributeValue>[]
     */
    private $keys;

    /**
     * This is a legacy parameter. Use `ProjectionExpression` instead. For more information, see Legacy Conditional
     * Parameters [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.html
     *
     * @var string[]|null
     */
    private $attributesToGet;

    /**
     * The consistency of a read operation. If set to `true`, then a strongly consistent read is used; otherwise, an
     * eventually consistent read is used.
     *
     * @var bool|null
     */
    private $consistentRead;

    /**
     * A string that identifies one or more attributes to retrieve from the table. These attributes can include scalars,
     * sets, or elements of a JSON document. The attributes in the `ProjectionExpression` must be separated by commas.
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
     * For more information on expression attribute names, see Accessing Item Attributes [^2] in the *Amazon DynamoDB
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ReservedWords.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.AccessingItemAttributes.html
     *
     * @var array<string, string>|null
     */
    private $expressionAttributeNames;

    /**
     * @param array{
     *   Keys: array[],
     *   AttributesToGet?: string[]|null,
     *   ConsistentRead?: bool|null,
     *   ProjectionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->keys = $input['Keys'] ?? $this->throwException(new InvalidArgument('Missing required field "Keys".'));
        $this->attributesToGet = $input['AttributesToGet'] ?? null;
        $this->consistentRead = $input['ConsistentRead'] ?? null;
        $this->projectionExpression = $input['ProjectionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;
    }

    /**
     * @param array{
     *   Keys: array[],
     *   AttributesToGet?: string[]|null,
     *   ConsistentRead?: bool|null,
     *   ProjectionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     * }|KeysAndAttributes $input
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
        return $this->keys;
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
        $v = $this->keys;

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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
