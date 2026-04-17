<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\SchemaRegistryEventRecordFormat;

/**
 * Specific configuration settings for a Kafka schema registry.
 */
final class KafkaSchemaRegistryConfig
{
    /**
     * The URI for your schema registry. The correct URI format depends on the type of schema registry you're using.
     *
     * - For Glue schema registries, use the ARN of the registry.
     * - For Confluent schema registries, use the URL of the registry.
     *
     * @var string|null
     */
    private $schemaRegistryUri;

    /**
     * The record format that Lambda delivers to your function after schema validation.
     *
     * - Choose `JSON` to have Lambda deliver the record to your function as a standard JSON object.
     * - Choose `SOURCE` to have Lambda deliver the record to your function in its original source format. Lambda removes
     *   all schema metadata, such as the schema ID, before sending the record to your function.
     *
     * @var SchemaRegistryEventRecordFormat::*|null
     */
    private $eventRecordFormat;

    /**
     * An array of access configuration objects that tell Lambda how to authenticate with your schema registry.
     *
     * @var KafkaSchemaRegistryAccessConfig[]|null
     */
    private $accessConfigs;

    /**
     * An array of schema validation configuration objects, which tell Lambda the message attributes you want to validate
     * and filter using your schema registry.
     *
     * @var KafkaSchemaValidationConfig[]|null
     */
    private $schemaValidationConfigs;

    /**
     * @param array{
     *   SchemaRegistryURI?: string|null,
     *   EventRecordFormat?: SchemaRegistryEventRecordFormat::*|null,
     *   AccessConfigs?: array<KafkaSchemaRegistryAccessConfig|array>|null,
     *   SchemaValidationConfigs?: array<KafkaSchemaValidationConfig|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->schemaRegistryUri = $input['SchemaRegistryURI'] ?? null;
        $this->eventRecordFormat = $input['EventRecordFormat'] ?? null;
        $this->accessConfigs = isset($input['AccessConfigs']) ? array_map([KafkaSchemaRegistryAccessConfig::class, 'create'], $input['AccessConfigs']) : null;
        $this->schemaValidationConfigs = isset($input['SchemaValidationConfigs']) ? array_map([KafkaSchemaValidationConfig::class, 'create'], $input['SchemaValidationConfigs']) : null;
    }

    /**
     * @param array{
     *   SchemaRegistryURI?: string|null,
     *   EventRecordFormat?: SchemaRegistryEventRecordFormat::*|null,
     *   AccessConfigs?: array<KafkaSchemaRegistryAccessConfig|array>|null,
     *   SchemaValidationConfigs?: array<KafkaSchemaValidationConfig|array>|null,
     * }|KafkaSchemaRegistryConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return KafkaSchemaRegistryAccessConfig[]
     */
    public function getAccessConfigs(): array
    {
        return $this->accessConfigs ?? [];
    }

    /**
     * @return SchemaRegistryEventRecordFormat::*|null
     */
    public function getEventRecordFormat(): ?string
    {
        return $this->eventRecordFormat;
    }

    public function getSchemaRegistryUri(): ?string
    {
        return $this->schemaRegistryUri;
    }

    /**
     * @return KafkaSchemaValidationConfig[]
     */
    public function getSchemaValidationConfigs(): array
    {
        return $this->schemaValidationConfigs ?? [];
    }
}
