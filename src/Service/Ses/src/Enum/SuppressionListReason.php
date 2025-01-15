<?php

namespace AsyncAws\Ses\Enum;

/**
 * The reason that the address was added to the suppression list for your account. The value can be one of the
 * following:
 *
 * - `COMPLAINT` – Amazon SES added an email address to the suppression list for your account because a message sent
 *   to that address results in a complaint.
 * - `BOUNCE` – Amazon SES added an email address to the suppression list for your account because a message sent to
 *   that address results in a hard bounce.
 */
final class SuppressionListReason
{
    public const BOUNCE = 'BOUNCE';
    public const COMPLAINT = 'COMPLAINT';

    public static function exists(string $value): bool
    {
        return isset([
            self::BOUNCE => true,
            self::COMPLAINT => true,
        ][$value]);
    }
}
