<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * Information that your user pool responds with in `AuthenticationResult`when you configure it to remember devices and
 * a user signs in with an unrecognized device. Amazon Cognito presents a new device key that you can use to set up
 * device authentication [^1] in a "Remember me on this device" authentication model.
 *
 * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html
 */
final class NewDeviceMetadataType
{
    /**
     * The device key, an identifier used in generating the `DEVICE_PASSWORD_VERIFIER` for device SRP authentication.
     *
     * @var string|null
     */
    private $deviceKey;

    /**
     * The device group key, an identifier used in generating the `DEVICE_PASSWORD_VERIFIER` for device SRP authentication.
     *
     * @var string|null
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

    /**
     * @param array{
     *   DeviceKey?: null|string,
     *   DeviceGroupKey?: null|string,
     * }|NewDeviceMetadataType $input
     */
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
