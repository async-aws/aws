<?php

namespace AsyncAws\Ses\Enum;

/**
 * The status of the MAIL FROM domain. This status can have the following values:
 *
 * - `PENDING` – Amazon SES hasn't started searching for the MX record yet.
 * - `SUCCESS` – Amazon SES detected the required MX record for the MAIL FROM domain.
 * - `FAILED` – Amazon SES can't find the required MX record, or the record no longer exists.
 * - `TEMPORARY_FAILURE` – A temporary issue occurred, which prevented Amazon SES from determining the status of the
 *   MAIL FROM domain.
 */
final class MailFromDomainStatus
{
    public const FAILED = 'FAILED';
    public const PENDING = 'PENDING';
    public const SUCCESS = 'SUCCESS';
    public const TEMPORARY_FAILURE = 'TEMPORARY_FAILURE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FAILED => true,
            self::PENDING => true,
            self::SUCCESS => true,
            self::TEMPORARY_FAILURE => true,
        ][$value]);
    }
}
