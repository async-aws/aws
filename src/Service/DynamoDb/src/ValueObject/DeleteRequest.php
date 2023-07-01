<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents a request to perform a `DeleteItem` operation on an item.
 */
final class DeleteRequest
{
    /**
     * A map of attribute name to attribute values, representing the primary key of the item to delete. All of the table's
     * primary key attributes must be specified, and their data types must match those of the table's key schema.
     *
     * @var array<string, AttributeValue>
     */
    private $key;

    /**
     * @param array{
     *   Key: array<string, AttributeValue|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = isset($input['Key']) ? array_map([AttributeValue::class, 'create'], $input['Key']) : $this->throwException(new InvalidArgument('Missing required field "Key".'));
    }

    /**
     * @param array{
     *   Key: array<string, AttributeValue|array>,
     * }|DeleteRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getKey(): array
    {
        return $this->key;
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
