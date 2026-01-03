<?php

namespace AsyncAws\MediaConvert\Enum;

final class ShareStatus
{
    public const INITIATED = 'INITIATED';
    public const NOT_SHARED = 'NOT_SHARED';
    public const SHARED = 'SHARED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::INITIATED => true,
            self::NOT_SHARED => true,
            self::SHARED => true,
        ][$value]);
    }
}
