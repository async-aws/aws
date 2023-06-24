<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * Thing group properties.
 */
final class ThingGroupProperties
{
    /**
     * The thing group description.
     */
    private $thingGroupDescription;

    /**
     * The thing group attributes in JSON format.
     */
    private $attributePayload;

    /**
     * @param array{
     *   thingGroupDescription?: null|string,
     *   attributePayload?: null|AttributePayload|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->thingGroupDescription = $input['thingGroupDescription'] ?? null;
        $this->attributePayload = isset($input['attributePayload']) ? AttributePayload::create($input['attributePayload']) : null;
    }

    /**
     * @param array{
     *   thingGroupDescription?: null|string,
     *   attributePayload?: null|AttributePayload|array,
     * }|ThingGroupProperties $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributePayload(): ?AttributePayload
    {
        return $this->attributePayload;
    }

    public function getThingGroupDescription(): ?string
    {
        return $this->thingGroupDescription;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->thingGroupDescription) {
            $payload['thingGroupDescription'] = $v;
        }
        if (null !== $v = $this->attributePayload) {
            $payload['attributePayload'] = $v->requestBody();
        }

        return $payload;
    }
}
