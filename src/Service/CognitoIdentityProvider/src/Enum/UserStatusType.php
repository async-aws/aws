<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * The user status. This can be one of the following:.
 *
 * - UNCONFIRMED - User has been created but not confirmed.
 * - CONFIRMED - User has been confirmed.
 * - EXTERNAL_PROVIDER - User signed in with a third-party IdP.
 * - ARCHIVED - User is no longer active.
 * - UNKNOWN - User status isn't known.
 * - RESET_REQUIRED - User is confirmed, but the user must request a code and reset their password before they can sign
 *   in.
 * - FORCE_CHANGE_PASSWORD - The user is confirmed and the user can sign in using a temporary password, but on first
 *   sign-in, the user must change their password to a new value before doing anything else.
 */
final class UserStatusType
{
    public const ARCHIVED = 'ARCHIVED';
    public const COMPROMISED = 'COMPROMISED';
    public const CONFIRMED = 'CONFIRMED';
    public const FORCE_CHANGE_PASSWORD = 'FORCE_CHANGE_PASSWORD';
    public const RESET_REQUIRED = 'RESET_REQUIRED';
    public const UNCONFIRMED = 'UNCONFIRMED';
    public const UNKNOWN = 'UNKNOWN';

    public static function exists(string $value): bool
    {
        return isset([
            self::ARCHIVED => true,
            self::COMPROMISED => true,
            self::CONFIRMED => true,
            self::FORCE_CHANGE_PASSWORD => true,
            self::RESET_REQUIRED => true,
            self::UNCONFIRMED => true,
            self::UNKNOWN => true,
        ][$value]);
    }
}
