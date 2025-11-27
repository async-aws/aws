<?php

namespace AsyncAws\Rekognition\Enum;

final class OrientationCorrection
{
    public const ROTATE_0 = 'ROTATE_0';
    public const ROTATE_180 = 'ROTATE_180';
    public const ROTATE_270 = 'ROTATE_270';
    public const ROTATE_90 = 'ROTATE_90';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ROTATE_0 => true,
            self::ROTATE_180 => true,
            self::ROTATE_270 => true,
            self::ROTATE_90 => true,
        ][$value]);
    }
}
