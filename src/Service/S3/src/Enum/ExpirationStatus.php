<?php

namespace AsyncAws\S3\Enum;

final class ExpirationStatus
{
    public const DISABLED = 'Disabled';
    public const ENABLED = 'Enabled';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
