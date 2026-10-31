<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\KafkaEventSourceMappingConsumptionMode;

/**
 * Specific configuration settings for a self-managed Apache Kafka event source.
 */
final class SelfManagedKafkaEventSourceConfig
{
    /**
     * The identifier for the Kafka consumer group to join. The consumer group ID must be unique among all your Kafka event
     * sources. After creating a Kafka event source mapping with the consumer group ID specified, you cannot update this
     * value. For more information, see Customizable consumer group ID [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/with-kafka-process.html#services-smaa-topic-add
     *
     * @var string|null
     */
    private $consumerGroupId;

    /**
     * Specific configuration settings for a Kafka schema registry.
     *
     * @var KafkaSchemaRegistryConfig|null
     */
    private $schemaRegistryConfig;

    /**
     * The mode that Lambda uses to consume records from your self-managed Apache Kafka topic. The default is `Stream`.
     *
     * - `Stream` – Lambda consumes records using a Kafka consumer group and preserves the order of records within each
     *   partition. This is the default when you omit `ConsumptionMode`.
     * - `Queue` – Lambda consumes records using a Kafka share group. Multiple consumers can process records from the same
     *   partitions concurrently. Queue mode does not preserve per-partition order. Queue mode requires an Apache Kafka
     *   cluster running version 4.2 or later with share groups enabled.
     *
     * You cannot change `ConsumptionMode` after you create the event source mapping. To use a different consumption mode,
     * create a new event source mapping.
     *
     * When `ConsumptionMode` is `Queue`, the `StartingPosition`, `StartingPositionTimestamp`, and
     * `MaximumBatchingWindowInSeconds` parameters are not supported.
     *
     * @var KafkaEventSourceMappingConsumptionMode::*|null
     */
    private $consumptionMode;

    /**
     * @param array{
     *   ConsumerGroupId?: string|null,
     *   SchemaRegistryConfig?: KafkaSchemaRegistryConfig|array|null,
     *   ConsumptionMode?: KafkaEventSourceMappingConsumptionMode::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->consumerGroupId = $input['ConsumerGroupId'] ?? null;
        $this->schemaRegistryConfig = isset($input['SchemaRegistryConfig']) ? KafkaSchemaRegistryConfig::create($input['SchemaRegistryConfig']) : null;
        $this->consumptionMode = $input['ConsumptionMode'] ?? null;
    }

    /**
     * @param array{
     *   ConsumerGroupId?: string|null,
     *   SchemaRegistryConfig?: KafkaSchemaRegistryConfig|array|null,
     *   ConsumptionMode?: KafkaEventSourceMappingConsumptionMode::*|null,
     * }|SelfManagedKafkaEventSourceConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConsumerGroupId(): ?string
    {
        return $this->consumerGroupId;
    }

    /**
     * @return KafkaEventSourceMappingConsumptionMode::*|null
     */
    public function getConsumptionMode(): ?string
    {
        return $this->consumptionMode;
    }

    public function getSchemaRegistryConfig(): ?KafkaSchemaRegistryConfig
    {
        return $this->schemaRegistryConfig;
    }
}
