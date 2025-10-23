<?php

namespace AsyncAws\Iot\ValueObject;

/**
 * An object that represents the connection attribute, thing attribute, and the user property key.
 */
final class PropagatingAttribute
{
    /**
     * The key of the user property key-value pair.
     *
     * @var string|null
     */
    private $userPropertyKey;

    /**
     * The user-defined thing attribute that is propagating for MQTT 5 message enrichment.
     *
     * @var string|null
     */
    private $thingAttribute;

    /**
     * The attribute associated with the connection between a device and Amazon Web Services IoT Core.
     *
     * @var string|null
     */
    private $connectionAttribute;

    /**
     * @param array{
     *   userPropertyKey?: string|null,
     *   thingAttribute?: string|null,
     *   connectionAttribute?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->userPropertyKey = $input['userPropertyKey'] ?? null;
        $this->thingAttribute = $input['thingAttribute'] ?? null;
        $this->connectionAttribute = $input['connectionAttribute'] ?? null;
    }

    /**
     * @param array{
     *   userPropertyKey?: string|null,
     *   thingAttribute?: string|null,
     *   connectionAttribute?: string|null,
     * }|PropagatingAttribute $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getConnectionAttribute(): ?string
    {
        return $this->connectionAttribute;
    }

    public function getThingAttribute(): ?string
    {
        return $this->thingAttribute;
    }

    public function getUserPropertyKey(): ?string
    {
        return $this->userPropertyKey;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->userPropertyKey) {
            $payload['userPropertyKey'] = $v;
        }
        if (null !== $v = $this->thingAttribute) {
            $payload['thingAttribute'] = $v;
        }
        if (null !== $v = $this->connectionAttribute) {
            $payload['connectionAttribute'] = $v;
        }

        return $payload;
    }
}
