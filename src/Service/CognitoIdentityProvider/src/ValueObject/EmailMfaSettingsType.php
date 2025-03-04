<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * User preferences for multi-factor authentication with email messages. Activates or deactivates email MFA and sets it
 * as the preferred MFA method when multiple methods are available. To activate this setting, your user pool must be in
 * the Essentials tier [^1] or higher.
 *
 * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/feature-plans-features-essentials.html
 */
final class EmailMfaSettingsType
{
    /**
     * Specifies whether email message MFA is active for a user. When the value of this parameter is `Enabled`, the user
     * will be prompted for MFA during all sign-in attempts, unless device tracking is turned on and the device has been
     * trusted.
     *
     * @var bool|null
     */
    private $enabled;

    /**
     * Specifies whether email message MFA is the user's preferred method.
     *
     * @var bool|null
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

    /**
     * @param array{
     *   Enabled?: null|bool,
     *   PreferredMfa?: null|bool,
     * }|EmailMfaSettingsType $input
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
