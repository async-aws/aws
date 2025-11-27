<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. Ignore this setting unless Nagra support directs you to specify a value. When you don't specify a value
 * here, the Nagra NexGuard library uses its default value.
 */
final class WatermarkingStrength
{
    public const DEFAULT = 'DEFAULT';
    public const LIGHTER = 'LIGHTER';
    public const LIGHTEST = 'LIGHTEST';
    public const STRONGER = 'STRONGER';
    public const STRONGEST = 'STRONGEST';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::LIGHTER => true,
            self::LIGHTEST => true,
            self::STRONGER => true,
            self::STRONGEST => true,
        ][$value]);
    }
}
