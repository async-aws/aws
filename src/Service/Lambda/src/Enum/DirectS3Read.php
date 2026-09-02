<?php

namespace AsyncAws\Lambda\Enum;

final class DirectS3Read
{
    public const AUTO = 'AUTO';
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
