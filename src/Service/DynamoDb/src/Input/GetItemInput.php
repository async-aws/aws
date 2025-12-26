<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\AttributeValue;

/**
 * Represents the input of a `GetItem` operation.
 */
final class GetItemInput extends Input
{
    /**
     * The name of the table containing the requested item. You can also provide the Amazon Resource Name (ARN) of the table
     * in this parameter.
     *
     * @required
     *
     * @var string|null
     */
    private $tableName;

    /**
     * A map of attribute names to `AttributeValue` objects, representing the primary key of the item to retrieve.
     *
     * For the primary key, you must provide all of the attributes. For example, with a simple primary key, you only need to
     * provide a value for the partition key. For a composite primary key, you must provide values for both the partition
     * key and the sort key.
     *
     * @required
     *
     * @var array<string, AttributeValue>|null
     */
    private $key;

    /**
     * This is a legacy parameter. Use `ProjectionExpression` instead. For more information, see AttributesToGet [^1] in the
     * *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.AttributesToGet.html
     *
     * @var string[]|null
     */
    private $attributesToGet;

    /**
     * Determines the read consistency model: If set to `true`, then the operation uses strongly consistent reads;
     * otherwise, the operation uses eventually consistent reads.
     *
     * @var bool|null
     */
    private $consistentRead;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $returnConsumedCapacity;

    /**
     * A string that identifies one or more attributes to retrieve from the table. These attributes can include scalars,
     * sets, or elements of a JSON document. The attributes in the expression must be separated by commas.
     *
     * If no attribute names are specified, then all attributes are returned. If any of the requested attributes are not
     * found, they do not appear in the result.
     *
     * For more information, see Specifying Item Attributes [^1] in the *Amazon DynamoDB Developer Guide*.
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
     * For more information on expression attribute names, see Specifying Item Attributes [^2] in the *Amazon DynamoDB
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
     *   TableName?: string,
     *   Key?: array<string, AttributeValue|array>,
     *   AttributesToGet?: string[]|null,
     *   ConsistentRead?: bool|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ProjectionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->tableName = $input['TableName'] ?? null;

        if (isset($input['Key'])) {
            $this->key = [];
            foreach ($input['Key'] as $key => $item) {
                $this->key[$key] = AttributeValue::create($item);
            }
        }
        $this->attributesToGet = $input['AttributesToGet'] ?? null;
        $this->consistentRead = $input['ConsistentRead'] ?? null;
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->projectionExpression = $input['ProjectionExpression'] ?? null;
        $this->expressionAttributeNames = $input['ExpressionAttributeNames'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   TableName?: string,
     *   Key?: array<string, AttributeValue|array>,
     *   AttributesToGet?: string[]|null,
     *   ConsistentRead?: bool|null,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ProjectionExpression?: string|null,
     *   ExpressionAttributeNames?: array<string, string>|null,
     *   '@region'?: string|null,
     * }|GetItemInput $input
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
     * @return array<string, AttributeValue>
     */
    public function getKey(): array
    {
        return $this->key ?? [];
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
            'X-Amz-Target' => 'DynamoDB_20120810.GetItem',
            'Accept' => 'application/json',
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

    public function setConsistentRead(?bool $value): self
    {
        $this->consistentRead = $value;

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
    public function setKey(array $value): self
    {
        $this->key = $value;

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

    public function setTableName(?string $value): self
    {
        $this->tableName = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->tableName) {
            throw new InvalidArgument(\sprintf('Missing parameter "TableName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['TableName'] = $v;
        if (null === $v = $this->key) {
            throw new InvalidArgument(\sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['Key'] = new \stdClass();
        } else {
            $payload['Key'] = [];
            foreach ($v as $name => $mv) {
                $payload['Key'][$name] = $mv->requestBody();
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
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
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
