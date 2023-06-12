<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * Contextual data, such as the user's device fingerprint, IP address, or location, used for evaluating the risk of an
 * unexpected event by Amazon Cognito advanced security.
 */
final class UserContextDataType
{
    /**
     * The source IP address of your user's device.
     */
    private $ipAddress;

    /**
     * Encoded device-fingerprint details that your app collected with the Amazon Cognito context data collection library.
     * For more information, see Adding user device and session data to API requests [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pool-settings-adaptive-authentication.html#user-pool-settings-adaptive-authentication-device-fingerprint
     */
    private $encodedData;

    /**
     * @param array{
     *   IpAddress?: null|string,
     *   EncodedData?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->ipAddress = $input['IpAddress'] ?? null;
        $this->encodedData = $input['EncodedData'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEncodedData(): ?string
    {
        return $this->encodedData;
    }

    public function getIpAddress(): ?string
    {
        return $this->ipAddress;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->ipAddress) {
            $payload['IpAddress'] = $v;
        }
        if (null !== $v = $this->encodedData) {
            $payload['EncodedData'] = $v;
        }

        return $payload;
    }
}
