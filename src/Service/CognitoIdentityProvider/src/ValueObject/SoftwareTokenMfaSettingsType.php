<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The time-based one-time password software token MFA settings.
 */
final class SoftwareTokenMfaSettingsType
{
    /**
     * Specifies whether software token MFA is enabled. If an MFA type is enabled for a user, the user will be prompted for
     * MFA during all sign in attempts, unless device tracking is turned on and the device has been trusted.
     */
    private $Enabled;

    /**
     * Specifies whether software token MFA is the preferred MFA method.
     */
    private $PreferredMfa;

    /**
     * @param array{
     *   Enabled?: null|bool,
     *   PreferredMfa?: null|bool,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Enabled = $input['Enabled'] ?? null;
        $this->PreferredMfa = $input['PreferredMfa'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEnabled(): ?bool
    {
        return $this->Enabled;
    }

    public function getPreferredMfa(): ?bool
    {
        return $this->PreferredMfa;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Enabled) {
            $payload['Enabled'] = (bool) $v;
        }
        if (null !== $v = $this->PreferredMfa) {
            $payload['PreferredMfa'] = (bool) $v;
        }

        return $payload;
    }
}
