<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The attribute payload.
 */
final class AttributePayload
{
    /**
     * A JSON string containing up to three key-value pair in JSON format. For example:
     *
     * `{\"attributes\":{\"string1\":\"string2\"}}`
     *
     * @var array<string, string>|null
     */
    private $attributes;

    /**
     * Specifies whether the list of attributes provided in the `AttributePayload` is merged with the attributes stored in
     * the registry, instead of overwriting them.
     *
     * To remove an attribute, call `UpdateThing` with an empty attribute value.
     *
     * > The `merge` attribute is only valid when calling `UpdateThing` or `UpdateThingGroup`.
     *
     * @var bool|null
     */
    private $merge;

    /**
     * @param array{
     *   attributes?: null|array<string, string>,
     *   merge?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->attributes = $input['attributes'] ?? null;
        $this->merge = $input['merge'] ?? null;
    }

    /**
     * @param array{
     *   attributes?: null|array<string, string>,
     *   merge?: null|bool,
     * }|AttributePayload $input
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

    public function getMerge(): ?bool
    {
        return $this->merge;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->attributes) {
            if (empty($v)) {
                $payload['attributes'] = new \stdClass();
            } else {
                $payload['attributes'] = [];
                foreach ($v as $name => $mv) {
                    $payload['attributes'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->merge) {
            $payload['merge'] = (bool) $v;
        }

        return $payload;
    }
}
