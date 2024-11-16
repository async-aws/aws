<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The ThingTypeProperties contains information about the thing type including: a thing type description, and a list of
 * searchable thing attribute names.
 */
final class ThingTypeProperties
{
    /**
     * The description of the thing type.
     *
     * @var string|null
     */
    private $thingTypeDescription;

    /**
     * A list of searchable thing attribute names.
     *
     * @var string[]|null
     */
    private $searchableAttributes;

    /**
     * The configuration to add user-defined properties to enrich MQTT 5 messages.
     *
     * @var Mqtt5Configuration|null
     */
    private $mqtt5Configuration;

    /**
     * @param array{
     *   thingTypeDescription?: null|string,
     *   searchableAttributes?: null|string[],
     *   mqtt5Configuration?: null|Mqtt5Configuration|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->thingTypeDescription = $input['thingTypeDescription'] ?? null;
        $this->searchableAttributes = $input['searchableAttributes'] ?? null;
        $this->mqtt5Configuration = isset($input['mqtt5Configuration']) ? Mqtt5Configuration::create($input['mqtt5Configuration']) : null;
    }

    /**
     * @param array{
     *   thingTypeDescription?: null|string,
     *   searchableAttributes?: null|string[],
     *   mqtt5Configuration?: null|Mqtt5Configuration|array,
     * }|ThingTypeProperties $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getMqtt5Configuration(): ?Mqtt5Configuration
    {
        return $this->mqtt5Configuration;
    }

    /**
     * @return string[]
     */
    public function getSearchableAttributes(): array
    {
        return $this->searchableAttributes ?? [];
    }

    public function getThingTypeDescription(): ?string
    {
        return $this->thingTypeDescription;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->thingTypeDescription) {
            $payload['thingTypeDescription'] = $v;
        }
        if (null !== $v = $this->searchableAttributes) {
            $index = -1;
            $payload['searchableAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['searchableAttributes'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->mqtt5Configuration) {
            $payload['mqtt5Configuration'] = $v->requestBody();
        }

        return $payload;
    }
}
