<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The new device metadata type.
 */
final class NewDeviceMetadataType
{
    /**
     * The device key.
     */
    private $deviceKey;

    /**
     * The device group key.
     */
    private $deviceGroupKey;

    /**
     * @param array{
     *   DeviceKey?: null|string,
     *   DeviceGroupKey?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->deviceKey = $input['DeviceKey'] ?? null;
        $this->deviceGroupKey = $input['DeviceGroupKey'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDeviceGroupKey(): ?string
    {
        return $this->deviceGroupKey;
    }

    public function getDeviceKey(): ?string
    {
        return $this->deviceKey;
    }
}
