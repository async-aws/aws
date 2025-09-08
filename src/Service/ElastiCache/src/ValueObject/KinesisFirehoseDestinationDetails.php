<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * The configuration details of the Kinesis Data Firehose destination.
 */
final class KinesisFirehoseDestinationDetails
{
    /**
     * The name of the Kinesis Data Firehose delivery stream.
     *
     * @var string|null
     */
    private $deliveryStream;

    /**
     * @param array{
     *   DeliveryStream?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deliveryStream = $input['DeliveryStream'] ?? null;
    }

    /**
     * @param array{
     *   DeliveryStream?: string|null,
     * }|KinesisFirehoseDestinationDetails $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeliveryStream(): ?string
    {
        return $this->deliveryStream;
    }
}
