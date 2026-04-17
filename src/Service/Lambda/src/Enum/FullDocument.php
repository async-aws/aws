<?php

namespace AsyncAws\Lambda\Enum;

final class FullDocument
{
    public const DEFAULT = 'Default';
    public const UPDATE_LOOKUP = 'UpdateLookup';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::UPDATE_LOOKUP => true,
        ][$value]);
    }
}
