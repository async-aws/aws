<?php

namespace AsyncAws\Ses\Enum;

/**
 * The DKIM authentication status of the identity. The status can be one of the following:
 *
 * - `PENDING` – The verification process was initiated, but Amazon SES hasn't yet detected the DKIM records in the
 *   DNS configuration for the domain.
 * - `SUCCESS` – The verification process completed successfully.
 * - `FAILED` – The verification process failed. This typically occurs when Amazon SES fails to find the DKIM records
 *   in the DNS configuration of the domain.
 * - `TEMPORARY_FAILURE` – A temporary issue is preventing Amazon SES from determining the DKIM authentication status
 *   of the domain.
 * - `NOT_STARTED` – The DKIM verification process hasn't been initiated for the domain.
 */
final class DkimStatus
{
    public const FAILED = 'FAILED';
    public const NOT_STARTED = 'NOT_STARTED';
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
            self::NOT_STARTED => true,
            self::PENDING => true,
            self::SUCCESS => true,
            self::TEMPORARY_FAILURE => true,
        ][$value]);
    }
}
