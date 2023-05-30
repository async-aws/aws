<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose Insert (INSERT) for this setting to include color metadata in this output. Choose Ignore (IGNORE) to exclude
 * color metadata from this output. If you don't specify a value, the service sets this to Insert by default.
 */
final class ColorMetadata
{
    public const IGNORE = 'IGNORE';
    public const INSERT = 'INSERT';

    public static function exists(string $value): bool
    {
        return isset([
            self::IGNORE => true,
            self::INSERT => true,
        ][$value]);
    }
}
