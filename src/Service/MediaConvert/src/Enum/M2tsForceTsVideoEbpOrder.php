<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Keep the default value unless you know that your audio EBP markers are incorrectly appearing before your video EBP
 * markers. To correct this problem, set this value to Force.
 */
final class M2tsForceTsVideoEbpOrder
{
    public const DEFAULT = 'DEFAULT';
    public const FORCE = 'FORCE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::FORCE => true,
        ][$value]);
    }
}
