<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Profile to specify the type of Apple ProRes codec to use for this output.
 */
final class ProresCodecProfile
{
    public const APPLE_PRORES_422 = 'APPLE_PRORES_422';
    public const APPLE_PRORES_422_HQ = 'APPLE_PRORES_422_HQ';
    public const APPLE_PRORES_422_LT = 'APPLE_PRORES_422_LT';
    public const APPLE_PRORES_422_PROXY = 'APPLE_PRORES_422_PROXY';
    public const APPLE_PRORES_4444 = 'APPLE_PRORES_4444';
    public const APPLE_PRORES_4444_XQ = 'APPLE_PRORES_4444_XQ';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::APPLE_PRORES_422 => true,
            self::APPLE_PRORES_422_HQ => true,
            self::APPLE_PRORES_422_LT => true,
            self::APPLE_PRORES_422_PROXY => true,
            self::APPLE_PRORES_4444 => true,
            self::APPLE_PRORES_4444_XQ => true,
        ][$value]);
    }
}
