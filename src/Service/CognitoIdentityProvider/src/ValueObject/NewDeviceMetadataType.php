<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The new device metadata from an authentication result.
 */
final class NewDeviceMetadataType
{
    /**
     * The device key.
     */
    private $DeviceKey;

    /**
     * The device group key.
     */
    private $DeviceGroupKey;

    /**
     * @param array{
     *   DeviceKey?: null|string,
     *   DeviceGroupKey?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->DeviceKey = $input['DeviceKey'] ?? null;
        $this->DeviceGroupKey = $input['DeviceGroupKey'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeviceGroupKey(): ?string
    {
        return $this->DeviceGroupKey;
    }

    public function getDeviceKey(): ?string
    {
        return $this->DeviceKey;
    }
}
