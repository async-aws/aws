<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The type used for enabling software token MFA at the user level. If an MFA type is activated for a user, the user
 * will be prompted for MFA during all sign-in attempts, unless device tracking is turned on and the device has been
 * trusted. If you want MFA to be applied selectively based on the assessed risk level of sign-in attempts, deactivate
 * MFA for users and turn on Adaptive Authentication for the user pool.
 */
final class SoftwareTokenMfaSettingsType
{
    /**
     * Specifies whether software token MFA is activated. If an MFA type is activated for a user, the user will be prompted
     * for MFA during all sign-in attempts, unless device tracking is turned on and the device has been trusted.
     */
    private $enabled;

    /**
     * Specifies whether software token MFA is the preferred MFA method.
     */
    private $preferredMfa;

    /**
     * @param array{
     *   Enabled?: null|bool,
     *   PreferredMfa?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->enabled = $input['Enabled'] ?? null;
        $this->preferredMfa = $input['PreferredMfa'] ?? null;
    }

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
