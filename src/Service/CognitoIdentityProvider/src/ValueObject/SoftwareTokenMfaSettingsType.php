<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * A user's preference for using time-based one-time password (TOTP) multi-factor authentication (MFA). Turns TOTP MFA
 * on and off, and can set TOTP as preferred when other MFA options are available. You can't turn off TOTP MFA for any
 * of your users when MFA is required in your user pool; you can only set the type that your user prefers.
 */
final class SoftwareTokenMfaSettingsType
{
    /**
     * Specifies whether software token MFA is activated. If an MFA type is activated for a user, the user will be prompted
     * for MFA during all sign-in attempts, unless device tracking is turned on and the device has been trusted.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * Specifies whether software token MFA is the preferred MFA method.
     *
     * @var bool|null
     */
    private $preferredMfa;

    /**
     * @param array{
     *   Enabled?: bool|null,
     *   PreferredMfa?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? null;
        $this->preferredMfa = $input['PreferredMfa'] ?? null;
    }

    /**
     * @param array{
     *   Enabled?: bool|null,
     *   PreferredMfa?: bool|null,
     * }|SoftwareTokenMfaSettingsType $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function getPreferredMfa(): ?bool
    {
        return $this->preferredMfa;
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
        if (null !== $v = $this->preferredMfa) {
            $payload['PreferredMfa'] = (bool) $v;
        }

        return $payload;
    }
}
