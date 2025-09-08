<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\ValueObject\KeysAndAttributes;

/**
 * Represents the input of a `BatchGetItem` operation.
 */
final class BatchGetItemInput extends Input
{
    /**
     * A map of one or more table names or table ARNs and, for each table, a map that describes one or more items to
     * retrieve from that table. Each table name or ARN can be used only once per `BatchGetItem` request.
     *
     * Each element in the map of items to retrieve consists of the following:
     *
     * - `ConsistentRead` - If `true`, a strongly consistent read is used; if `false` (the default), an eventually
     *   consistent read is used.
     * - `ExpressionAttributeNames` - One or more substitution tokens for attribute names in the `ProjectionExpression`
     *   parameter. The following are some use cases for using `ExpressionAttributeNames`:
     *
     *   - To access an attribute whose name conflicts with a DynamoDB reserved word.
     *   - To create a placeholder for repeating occurrences of an attribute name in an expression.
     *   - To prevent special characters in an attribute name from being misinterpreted in an expression.
     *
     *   Use the **#** character in an expression to dereference an attribute name. For example, consider the following
     *   attribute name:
     *
     *   - `Percentile`
     *
     *   The name of this attribute conflicts with a reserved word, so it cannot be used directly in an expression. (For the
     *   complete list of reserved words, see Reserved Words [^1] in the *Amazon DynamoDB Developer Guide*). To work around
     *   this, you could specify the following for `ExpressionAttributeNames`:
     *
     *   - `{"#P":"Percentile"}`
     *
     *   You could then use this substitution in an expression, as in this example:
     *
     *   - `#P = :val`
     *
     *   > Tokens that begin with the **:** character are *expression attribute values*, which are placeholders for the
     *   > actual value at runtime.
     *
     *   For more information about expression attribute names, see Accessing Item Attributes [^2] in the *Amazon DynamoDB
     *   Developer Guide*.
     * - `Keys` - An array of primary key attribute values that define specific items in the table. For each primary key,
     *   you must provide *all* of the key attributes. For example, with a simple primary key, you only need to provide the
     *   partition key value. For a composite key, you must provide *both* the partition key value and the sort key value.
     * - `ProjectionExpression` - A string that identifies one or more attributes to retrieve from the table. These
     *   attributes can include scalars, sets, or elements of a JSON document. The attributes in the expression must be
     *   separated by commas.
     *
     *   If no attribute names are specified, then all attributes are returned. If any of the requested attributes are not
     *   found, they do not appear in the result.
     *
     *   For more information, see Accessing Item Attributes [^3] in the *Amazon DynamoDB Developer Guide*.
     * - `AttributesToGet` - This is a legacy parameter. Use `ProjectionExpression` instead. For more information, see
     *   AttributesToGet [^4] in the *Amazon DynamoDB Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ReservedWords.html
     * [^2]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.AccessingItemAttributes.html
     * [^3]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Expressions.AccessingItemAttributes.html
     * [^4]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/LegacyConditionalParameters.AttributesToGet.html
     *
     * @required
     *
     * @var array<string, KeysAndAttributes>|null
     */
    private $requestItems;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $returnConsumedCapacity;

    /**
     * @param array{
     *   RequestItems?: array<string, KeysAndAttributes|array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        if (isset($input['RequestItems'])) {
            $this->requestItems = [];
            foreach ($input['RequestItems'] as $key => $item) {
                $this->requestItems[$key] = KeysAndAttributes::create($item);
            }
        }
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   RequestItems?: array<string, KeysAndAttributes|array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   '@region'?: string|null,
     * }|BatchGetItemInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, KeysAndAttributes>
     */
    public function getRequestItems(): array
    {
        return $this->requestItems ?? [];
    }

    /**
     * @return ReturnConsumedCapacity::*|null
     */
    public function getReturnConsumedCapacity(): ?string
    {
        return $this->returnConsumedCapacity;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.BatchGetItem',
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
     * @param array<string, KeysAndAttributes> $value
     */
    public function setRequestItems(array $value): self
    {
        $this->requestItems = $value;

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

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->requestItems) {
            throw new InvalidArgument(\sprintf('Missing parameter "RequestItems" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['RequestItems'] = new \stdClass();
        } else {
            $payload['RequestItems'] = [];
            foreach ($v as $name => $mv) {
                $payload['RequestItems'][$name] = $mv->requestBody();
            }
        }
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }

        return $payload;
    }
}
