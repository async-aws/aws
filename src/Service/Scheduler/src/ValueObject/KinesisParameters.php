<?php

namespace AsyncAws\Scheduler\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The templated target type for the Amazon Kinesis `PutRecord` [^1] API operation.
 *
 * [^1]: kinesis/latest/APIReference/API_PutRecord.html
 */
final class KinesisParameters
{
    /**
     * Specifies the shard to which EventBridge Scheduler sends the event. For more information, see Amazon Kinesis Data
     * Streams terminology and concepts [^1] in the *Amazon Kinesis Streams Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/streams/latest/dev/key-concepts.html
     */
    private $partitionKey;

    /**
     * @param array{
     *   PartitionKey: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->partitionKey = $input['PartitionKey'] ?? $this->throwException(new InvalidArgument('Missing required field "PartitionKey".'));
    }

    /**
     * @param array{
     *   PartitionKey: string,
     * }|KinesisParameters $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getPartitionKey(): string
    {
        return $this->partitionKey;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->partitionKey;
        $payload['PartitionKey'] = $v;

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
