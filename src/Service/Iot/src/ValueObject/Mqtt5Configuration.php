<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * The configuration to add user-defined properties to enrich MQTT 5 messages.
 */
final class Mqtt5Configuration
{
    /**
     * An object that represents the propagating thing attributes and the connection attributes.
     *
     * @var PropagatingAttribute[]|null
     */
    private $propagatingAttributes;

    /**
     * @param array{
     *   propagatingAttributes?: null|array<PropagatingAttribute|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->propagatingAttributes = isset($input['propagatingAttributes']) ? array_map([PropagatingAttribute::class, 'create'], $input['propagatingAttributes']) : null;
    }

    /**
     * @param array{
     *   propagatingAttributes?: null|array<PropagatingAttribute|array>,
     * }|Mqtt5Configuration $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return PropagatingAttribute[]
     */
    public function getPropagatingAttributes(): array
    {
        return $this->propagatingAttributes ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->propagatingAttributes) {
            $index = -1;
            $payload['propagatingAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['propagatingAttributes'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
