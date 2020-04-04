<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

final class ProvisionedThroughput
{
    /**
     * The maximum number of strongly consistent reads consumed per second before DynamoDB returns a `ThrottlingException`.
     * For more information, see Specifying Read and Write Requirements in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/WorkingWithTables.html#ProvisionedThroughput
     */
    private $ReadCapacityUnits;

    /**
     * The maximum number of writes consumed per second before DynamoDB returns a `ThrottlingException`. For more
     * information, see Specifying Read and Write Requirements in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/WorkingWithTables.html#ProvisionedThroughput
     */
    private $WriteCapacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits: string,
     *   WriteCapacityUnits: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ReadCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->WriteCapacityUnits = $input['WriteCapacityUnits'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReadCapacityUnits(): string
    {
        return $this->ReadCapacityUnits;
    }

    public function getWriteCapacityUnits(): string
    {
        return $this->WriteCapacityUnits;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->ReadCapacityUnits) {
            throw new InvalidArgument(sprintf('Missing parameter "ReadCapacityUnits" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ReadCapacityUnits'] = $v;
        if (null === $v = $this->WriteCapacityUnits) {
            throw new InvalidArgument(sprintf('Missing parameter "WriteCapacityUnits" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['WriteCapacityUnits'] = $v;

        return $payload;
    }
}
