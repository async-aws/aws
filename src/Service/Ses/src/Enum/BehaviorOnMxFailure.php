<?php

namespace AsyncAws\Ses\Enum;

/**
 * The action to take if the required MX record can't be found when you send an email. When you set this value to
 * `UseDefaultValue`, the mail is sent using *amazonses.com* as the MAIL FROM domain. When you set this value to
 * `RejectMessage`, the Amazon SES API v2 returns a `MailFromDomainNotVerified` error, and doesn't attempt to deliver
 * the email.
 *
 * These behaviors are taken when the custom MAIL FROM domain configuration is in the `Pending`, `Failed`, and
 * `TemporaryFailure` states.
 */
final class BehaviorOnMxFailure
{
    public const REJECT_MESSAGE = 'REJECT_MESSAGE';
    public const USE_DEFAULT_VALUE = 'USE_DEFAULT_VALUE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::REJECT_MESSAGE => true,
            self::USE_DEFAULT_VALUE => true,
        ][$value]);
    }
}
