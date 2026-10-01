<?php

namespace AsyncAws\S3Vectors\Enum;

final class IndexMode
{
    public const CLASSIC = 'CLASSIC';
    public const ENHANCED = 'ENHANCED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CLASSIC => true,
            self::ENHANCED => true,
        ][$value]);
    }
}
