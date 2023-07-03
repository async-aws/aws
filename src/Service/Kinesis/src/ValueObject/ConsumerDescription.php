<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Kinesis\Enum\ConsumerStatus;

/**
 * An object that represents the details of a registered consumer. This type of object is returned by
 * DescribeStreamConsumer.
 */
final class ConsumerDescription
{
    /**
     * The name of the consumer is something you choose when you register the consumer.
     *
     * @var string
     */
    private $consumerName;

    /**
     * When you register a consumer, Kinesis Data Streams generates an ARN for it. You need this ARN to be able to call
     * SubscribeToShard.
     *
     * If you delete a consumer and then create a new one with the same name, it won't have the same ARN. That's because
     * consumer ARNs contain the creation timestamp. This is important to keep in mind if you have IAM policies that
     * reference consumer ARNs.
     *
     * @var string
     */
    private $consumerArn;

    /**
     * A consumer can't read data while in the `CREATING` or `DELETING` states.
     *
     * @var ConsumerStatus::*
     */
    private $consumerStatus;

    /**
     * @var \DateTimeImmutable
     */
    private $consumerCreationTimestamp;

    /**
     * The ARN of the stream with which you registered the consumer.
     *
     * @var string
     */
    private $streamArn;

    /**
     * @param array{
     *   ConsumerName: string,
     *   ConsumerARN: string,
     *   ConsumerStatus: ConsumerStatus::*,
     *   ConsumerCreationTimestamp: \DateTimeImmutable,
     *   StreamARN: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->consumerName = $input['ConsumerName'] ?? $this->throwException(new InvalidArgument('Missing required field "ConsumerName".'));
        $this->consumerArn = $input['ConsumerARN'] ?? $this->throwException(new InvalidArgument('Missing required field "ConsumerARN".'));
        $this->consumerStatus = $input['ConsumerStatus'] ?? $this->throwException(new InvalidArgument('Missing required field "ConsumerStatus".'));
        $this->consumerCreationTimestamp = $input['ConsumerCreationTimestamp'] ?? $this->throwException(new InvalidArgument('Missing required field "ConsumerCreationTimestamp".'));
        $this->streamArn = $input['StreamARN'] ?? $this->throwException(new InvalidArgument('Missing required field "StreamARN".'));
    }

    /**
     * @param array{
     *   ConsumerName: string,
     *   ConsumerARN: string,
     *   ConsumerStatus: ConsumerStatus::*,
     *   ConsumerCreationTimestamp: \DateTimeImmutable,
     *   StreamARN: string,
     * }|ConsumerDescription $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConsumerArn(): string
    {
        return $this->consumerArn;
    }

    public function getConsumerCreationTimestamp(): \DateTimeImmutable
    {
        return $this->consumerCreationTimestamp;
    }

    public function getConsumerName(): string
    {
        return $this->consumerName;
    }

    /**
     * @return ConsumerStatus::*
     */
    public function getConsumerStatus(): string
    {
        return $this->consumerStatus;
    }

    public function getStreamArn(): string
    {
        return $this->streamArn;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
