<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * A user's preference for using SMS message multi-factor authentication (MFA). Turns SMS MFA on and off, and can set
 * SMS as preferred when other MFA options are available. You can't turn off SMS MFA for any of your users when MFA is
 * required in your user pool; you can only set the type that your user prefers.
 */
final class SMSMfaSettingsType
{
    /**
     * Specifies whether SMS message MFA is activated. If an MFA type is activated for a user, the user will be prompted for
     * MFA during all sign-in attempts, unless device tracking is turned on and the device has been trusted.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * Specifies whether SMS is the preferred MFA method. If true, your user pool prompts the specified user for a code
     * delivered by SMS message after username-password sign-in succeeds.
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
     * }|SMSMfaSettingsType $input
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
