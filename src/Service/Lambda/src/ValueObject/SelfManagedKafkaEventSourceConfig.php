<?php

namespace AsyncAws\Lambda\ValueObject;

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
     * @param array{
     *   ConsumerGroupId?: string|null,
     *   SchemaRegistryConfig?: KafkaSchemaRegistryConfig|array|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->consumerGroupId = $input['ConsumerGroupId'] ?? null;
        $this->schemaRegistryConfig = isset($input['SchemaRegistryConfig']) ? KafkaSchemaRegistryConfig::create($input['SchemaRegistryConfig']) : null;
    }

    /**
     * @param array{
     *   ConsumerGroupId?: string|null,
     *   SchemaRegistryConfig?: KafkaSchemaRegistryConfig|array|null,
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

    public function getSchemaRegistryConfig(): ?KafkaSchemaRegistryConfig
    {
        return $this->schemaRegistryConfig;
    }
}
