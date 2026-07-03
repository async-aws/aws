<?php

namespace AsyncAws\Ses\Enum;

final class VerificationStatus
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
