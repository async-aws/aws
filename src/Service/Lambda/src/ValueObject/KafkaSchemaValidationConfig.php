<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\KafkaSchemaValidationAttribute;

/**
 * Specific schema validation configuration settings that tell Lambda the message attributes you want to validate and
 * filter using your schema registry.
 */
final class KafkaSchemaValidationConfig
{
    /**
     * The attributes you want your schema registry to validate and filter for. If you selected `JSON` as the
     * `EventRecordFormat`, Lambda also deserializes the selected message attributes.
     *
     * @var KafkaSchemaValidationAttribute::*|null
     */
    private $attribute;

    /**
     * @param array{
     *   Attribute?: KafkaSchemaValidationAttribute::*|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attribute = $input['Attribute'] ?? null;
    }

    /**
     * @param array{
     *   Attribute?: KafkaSchemaValidationAttribute::*|null,
     * }|KafkaSchemaValidationConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return KafkaSchemaValidationAttribute::*|null
     */
    public function getAttribute(): ?string
    {
        return $this->attribute;
    }
}
