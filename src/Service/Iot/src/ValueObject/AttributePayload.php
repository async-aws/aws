<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The attribute payload, which consists of up to three name/value pairs in a JSON document. For example:
 * `{\"attributes\":{\"string1\":\"string2\"}}`.
 */
final class AttributePayload
{
    /**
     * A JSON string containing up to three key-value pair in JSON format. For example:.
     */
    private $attributes;

    /**
     * Specifies whether the list of attributes provided in the `AttributePayload` is merged with the attributes stored in
     * the registry, instead of overwriting them.
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
