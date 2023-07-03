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
     *
     * @var int
     */
    private $readCapacityUnits;

    /**
     * The maximum number of writes consumed per second before DynamoDB returns a `ThrottlingException`. For more
     * information, see Specifying Read and Write Requirements [^1] in the *Amazon DynamoDB Developer Guide*.
     *
     * If read/write capacity mode is `PAY_PER_REQUEST` the value is set to 0.
     *
     * [^1]: https://docs.aws.amazon.com/amazondynamodb/latest/developerguide/ProvisionedThroughput.html
     *
     * @var int
     */
    private $writeCapacityUnits;

    /**
     * @param array{
     *   ReadCapacityUnits: int,
     *   WriteCapacityUnits: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->readCapacityUnits = $input['ReadCapacityUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "ReadCapacityUnits".'));
        $this->writeCapacityUnits = $input['WriteCapacityUnits'] ?? $this->throwException(new InvalidArgument('Missing required field "WriteCapacityUnits".'));
    }

    /**
     * @param array{
     *   ReadCapacityUnits: int,
     *   WriteCapacityUnits: int,
     * }|ProvisionedThroughput $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getReadCapacityUnits(): int
    {
        return $this->readCapacityUnits;
    }

    public function getWriteCapacityUnits(): int
    {
        return $this->writeCapacityUnits;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->readCapacityUnits;
        $payload['ReadCapacityUnits'] = $v;
        $v = $this->writeCapacityUnits;
        $payload['WriteCapacityUnits'] = $v;

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
