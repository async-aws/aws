<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Supports HbbTV specification as indicated.
 */
final class DashIsoHbbtvCompliance
{
    public const HBBTV_1_5 = 'HBBTV_1_5';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HBBTV_1_5 => true,
            self::NONE => true,
        ][$value]);
    }
}
