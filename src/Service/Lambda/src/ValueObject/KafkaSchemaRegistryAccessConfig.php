<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\KafkaSchemaRegistryAuthType;

/**
 * Specific access configuration settings that tell Lambda how to authenticate with your schema registry.
 *
 * If you're working with an Glue schema registry, don't provide authentication details in this object. Instead, ensure
 * that your execution role has the required permissions for Lambda to access your cluster.
 *
 * If you're working with a Confluent schema registry, choose the authentication method in the `Type` field, and provide
 * the Secrets Manager secret ARN in the `URI` field.
 */
final class KafkaSchemaRegistryAccessConfig
{
    /**
     * The type of authentication Lambda uses to access your schema registry.
     *
     * @var KafkaSchemaRegistryAuthType::*|null
     */
    private $type;

    /**
     * The URI of the secret (Secrets Manager secret ARN) to authenticate with your schema registry.
     *
     * @var string|null
     */
    private $uri;

    /**
     * @param array{
     *   Type?: KafkaSchemaRegistryAuthType::*|null,
     *   URI?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->type = $input['Type'] ?? null;
        $this->uri = $input['URI'] ?? null;
    }

    /**
     * @param array{
     *   Type?: KafkaSchemaRegistryAuthType::*|null,
     *   URI?: string|null,
     * }|KafkaSchemaRegistryAccessConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return KafkaSchemaRegistryAuthType::*|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }
}
