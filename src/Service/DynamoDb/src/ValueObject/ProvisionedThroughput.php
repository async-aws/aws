<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the provisioned throughput settings for the specified global secondary index.
 * For current minimum and maximum provisioned throughput values, see Service, Account, and Table Quotas in the *Amazon
 * DynamoDB Developer Guide*.
 *
 * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
 */
final class ProvisionedThroughput
{
    /**
     * The maximum number of strongly consistent reads consumed per second before DynamoDB returns a `ThrottlingException`.
     * For more information, see Specifying Read and Write Requirements in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/WorkingWithTables.html#ProvisionedThroughput
     */
    private $readCapacityUnits;

    /**
     * The maximum number of writes consumed per second before DynamoDB returns a `ThrottlingException`. For more
     * information, see Specifying Read and Write Requirements in the *Amazon DynamoDB Developer Guide*.
     *
     * @see https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/WorkingWithTables.html#ProvisionedThroughput
     */
    private $writeCapacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits: string,
     *   WriteCapacityUnits: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? null;
        $this->writeCapacityUnits = $input['WriteCapacityUnits'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReadCapacityUnits(): string
    {
        return $this->readCapacityUnits;
    }

    public function getWriteCapacityUnits(): string
    {
        return $this->writeCapacityUnits;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->readCapacityUnits) {
            throw new InvalidArgument(sprintf('Missing parameter "ReadCapacityUnits" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ReadCapacityUnits'] = $v;
        if (null === $v = $this->writeCapacityUnits) {
            throw new InvalidArgument(sprintf('Missing parameter "WriteCapacityUnits" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['WriteCapacityUnits'] = $v;

        return $payload;
    }
}
