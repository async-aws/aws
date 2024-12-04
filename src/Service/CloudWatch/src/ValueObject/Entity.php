<?php

namespace AsyncAws\CloudWatch\ValueObject;

/**
 * An entity associated with metrics, to allow for finding related telemetry. An entity is typically a resource or
 * service within your system. For example, metrics from an Amazon EC2 instance could be associated with that instance
 * as the entity. Similarly, metrics from a service that you own could be associated with that service as the entity.
 */
final class Entity
{
    /**
     * The attributes of the entity which identify the specific entity, as a list of key-value pairs. Entities with the same
     * `KeyAttributes` are considered to be the same entity. For an entity to be valid, the `KeyAttributes` must exist and
     * be formatted correctly.
     *
     * There are five allowed attributes (key names): `Type`, `ResourceType`, `Identifier`, `Name`, and `Environment`.
     *
     * For details about how to use the key attributes to specify an entity, see How to add related information to telemetry
     * [^1] in the *CloudWatch User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/adding-your-own-related-telemetry.html
     *
     * @var array<string, string>|null
     */
    private $keyAttributes;

    /**
     * Additional attributes of the entity that are not used to specify the identity of the entity. A list of key-value
     * pairs.
     *
     * For details about how to use the attributes, see How to add related information to telemetry [^1] in the *CloudWatch
     * User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AmazonCloudWatch/latest/monitoring/adding-your-own-related-telemetry.html
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * @param array{
     *   KeyAttributes?: null|array<string, string>,
     *   Attributes?: null|array<string, string>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->keyAttributes = $input['KeyAttributes'] ?? null;
        $this->attributes = $input['Attributes'] ?? null;
    }

    /**
     * @param array{
     *   KeyAttributes?: null|array<string, string>,
     *   Attributes?: null|array<string, string>,
     * }|Entity $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes ?? [];
    }

    /**
     * @return array<string, string>
     */
    public function getKeyAttributes(): array
    {
        return $this->keyAttributes ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->keyAttributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["KeyAttributes.entry.$index.key"] = $mapKey;
                $payload["KeyAttributes.entry.$index.value"] = $mapValue;
            }
        }
        if (null !== $v = $this->attributes) {
            $index = 0;
            foreach ($v as $mapKey => $mapValue) {
                ++$index;
                $payload["Attributes.entry.$index.key"] = $mapKey;
                $payload["Attributes.entry.$index.value"] = $mapValue;
            }
        }

        return $payload;
    }
}
