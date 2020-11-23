<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class DeleteRequest
{
    /**
     * A map of attribute name to attribute values, representing the primary key of the item to delete. All of the table's
     * primary key attributes must be specified, and their data types must match those of the table's key schema.
     */
    private $Key;

    /**
     * @param array{
     *   Key: array<string, AttributeValue>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = isset($input['Key']) ? array_map([AttributeValue::class, 'create'], $input['Key']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, AttributeValue>
     */
    public function getKey(): array
    {
        return $this->Key ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }

        if (empty($v)) {
            $payload['Key'] = new \stdClass();
        } else {
            foreach ($v as $name => $mv) {
                $payload['Key'][$name] = $mv->requestBody();
            }
        }

        return $payload;
    }
}
