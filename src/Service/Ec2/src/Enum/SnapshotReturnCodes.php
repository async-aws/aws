<?php

namespace AsyncAws\Ec2\Enum;

final class SnapshotReturnCodes
{
    public const CLIENT_ERROR = 'client-error';
    public const INTERNAL_ERROR = 'internal-error';
    public const MISSING_PERMISSIONS = 'missing-permissions';
    public const SKIPPED = 'skipped';
    public const SUCCESS = 'success';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CLIENT_ERROR => true,
            self::INTERNAL_ERROR => true,
            self::MISSING_PERMISSIONS => true,
            self::SKIPPED => true,
            self::SUCCESS => true,
        ][$value]);
    }
}
