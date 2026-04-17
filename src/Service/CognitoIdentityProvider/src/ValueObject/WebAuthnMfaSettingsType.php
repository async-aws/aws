<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * A user's preference for using passkey, or WebAuthn, multi-factor authentication (MFA). Turns passkey MFA on and off
 * for the user. Unlike other MFA settings types, this type doesn't include a `PreferredMfa` option because passkey MFA
 * applies only when passkey is the first authentication factor.
 */
final class WebAuthnMfaSettingsType
{
    /**
     * Specifies whether passkey MFA is activated for a user. When activated, the user's passkey authentication requires
     * user verification, and passkey sign-in is available when MFA is required. The user must also have at least one other
     * MFA method such as SMS, TOTP, or email activated to prevent account lockout.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * @param array{
     *   Enabled?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? null;
    }

    /**
     * @param array{
     *   Enabled?: bool|null,
     * }|WebAuthnMfaSettingsType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->enabled) {
            $payload['Enabled'] = (bool) $v;
        }

        return $payload;
    }
}
