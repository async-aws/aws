<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the provisioned throughput settings for a specified table or index. The settings can be modified using the
 * `UpdateTable` operation.
 *
 * For current minimum and maximum provisioned throughput values, see Service, Account, and Table Quotas [^1] in the
 * *Amazon DynamoDB Developer Guide*.
 *
 * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/Limits.html
 */
final class ProvisionedThroughput
{
    /**
     * The maximum number of strongly consistent reads consumed per second before DynamoDB returns a `ThrottlingException`.
     * For more information, see Specifying Read and Write Requirements [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * If read/write capacity mode is `PAY_PER_REQUEST` the value is set to 0.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughput.html
     */
    private $readCapacityUnits;

    /**
     * The maximum number of writes consumed per second before DynamoDB returns a `ThrottlingException`. For more
     * information, see Specifying Read and Write Requirements [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * If read/write capacity mode is `PAY_PER_REQUEST` the value is set to 0.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughput.html
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

    /**
     * @param array{
     *   ReadCapacityUnits: string,
     *   WriteCapacityUnits: string,
     * }|ProvisionedThroughput $input
     */
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
