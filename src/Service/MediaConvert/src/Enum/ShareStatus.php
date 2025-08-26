<?php

namespace AsyncAws\MediaConvert\Enum;

final class ShareStatus
{
    public const INITIATED = 'INITIATED';
    public const NOT_SHARED = 'NOT_SHARED';
    public const SHARED = 'SHARED';

    public static function exists(string $value): bool
    {
        return isset([
            self::INITIATED => true,
            self::NOT_SHARED => true,
            self::SHARED => true,
        ][$value]);
    }
}
