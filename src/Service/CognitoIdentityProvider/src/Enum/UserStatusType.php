<?php

namespace AsyncAws\CognitoIdentityProvider\Enum;

/**
 * The user status. Can be one of the following:.
 *
 * - UNCONFIRMED - User has been created but not confirmed.
 * - CONFIRMED - User has been confirmed.
 * - ARCHIVED - User is no longer active.
 * - COMPROMISED - User is disabled due to a potential security threat.
 * - UNKNOWN - User status is not known.
 * - RESET_REQUIRED - User is confirmed, but the user must request a code and reset his or her password before he or she
 *   can sign in.
 * - FORCE_CHANGE_PASSWORD - The user is confirmed and the user can sign in using a temporary password, but on first
 *   sign-in, the user must change his or her password to a new value before doing anything else.
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
