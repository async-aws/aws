<?php

namespace AsyncAws\DynamoDb\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;
use AsyncAws\DynamoDb\Enum\ReturnConsumedCapacity;
use AsyncAws\DynamoDb\Enum\ReturnItemCollectionMetrics;
use AsyncAws\DynamoDb\ValueObject\WriteRequest;

/**
 * Represents the input of a `BatchWriteItem` operation.
 */
final class BatchWriteItemInput extends Input
{
    /**
     * A map of one or more table names or table ARNs and, for each table, a list of operations to be performed
     * (`DeleteRequest` or `PutRequest`). Each element in the map consists of the following:
     *
     * - `DeleteRequest` - Perform a `DeleteItem` operation on the specified item. The item to be deleted is identified by a
     *   `Key` subelement:
     *
     *   - `Key` - A map of primary key attribute values that uniquely identify the item. Each entry in this map consists of
     *     an attribute name and an attribute value. For each primary key, you must provide *all* of the key attributes. For
     *     example, with a simple primary key, you only need to provide a value for the partition key. For a composite
     *     primary key, you must provide values for *both* the partition key and the sort key.
     *
     * - `PutRequest` - Perform a `PutItem` operation on the specified item. The item to be put is identified by an `Item`
     *   subelement:
     *
     *   - `Item` - A map of attributes and their values. Each entry in this map consists of an attribute name and an
     *     attribute value. Attribute values must not be null; string and binary type attributes must have lengths greater
     *     than zero; and set type attributes must not be empty. Requests that contain empty values are rejected with a
     *     `ValidationException` exception.
     *
     *     If you specify any attributes that are part of an index key, then the data types for those attributes must match
     *     those of the schema in the table's attribute definition.
     *
     * @required
     *
     * @var array<string, WriteRequest[]>|null
     */
    private $requestItems;

    /**
     * @var ReturnConsumedCapacity::*|null
     */
    private $returnConsumedCapacity;

    /**
     * Determines whether item collection metrics are returned. If set to `SIZE`, the response includes statistics about
     * item collections, if any, that were modified during the operation are returned in the response. If set to `NONE` (the
     * default), no statistics are returned.
     *
     * @var ReturnItemCollectionMetrics::*|null
     */
    private $returnItemCollectionMetrics;

    /**
     * @param array{
     *   RequestItems?: array<string, array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        if (isset($input['RequestItems'])) {
            $this->requestItems = [];
            foreach ($input['RequestItems'] as $key => $item) {
                $this->requestItems[$key] = array_map([WriteRequest::class, 'create'], $item);
            }
        }
        $this->returnConsumedCapacity = $input['ReturnConsumedCapacity'] ?? null;
        $this->returnItemCollectionMetrics = $input['ReturnItemCollectionMetrics'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   RequestItems?: array<string, array>,
     *   ReturnConsumedCapacity?: ReturnConsumedCapacity::*|null,
     *   ReturnItemCollectionMetrics?: ReturnItemCollectionMetrics::*|null,
     *   '@region'?: string|null,
     * }|BatchWriteItemInput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, WriteRequest[]>
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
     * @return ReturnItemCollectionMetrics::*|null
     */
    public function getReturnItemCollectionMetrics(): ?string
    {
        return $this->returnItemCollectionMetrics;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'DynamoDB_20120810.BatchWriteItem',
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
     * @param array<string, WriteRequest[]> $value
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

    /**
     * @param ReturnItemCollectionMetrics::*|null $value
     */
    public function setReturnItemCollectionMetrics(?string $value): self
    {
        $this->returnItemCollectionMetrics = $value;

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
                $index = -1;
                $payload['RequestItems'][$name] = [];
                foreach ($mv as $listValue) {
                    ++$index;
                    $payload['RequestItems'][$name][$index] = $listValue->requestBody();
                }
            }
        }
        if (null !== $v = $this->returnConsumedCapacity) {
            if (!ReturnConsumedCapacity::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnConsumedCapacity" for "%s". The value "%s" is not a valid "ReturnConsumedCapacity".', __CLASS__, $v));
            }
            $payload['ReturnConsumedCapacity'] = $v;
        }
        if (null !== $v = $this->returnItemCollectionMetrics) {
            if (!ReturnItemCollectionMetrics::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "ReturnItemCollectionMetrics" for "%s". The value "%s" is not a valid "ReturnItemCollectionMetrics".', __CLASS__, $v));
            }
            $payload['ReturnItemCollectionMetrics'] = $v;
        }

        return $payload;
    }
}
