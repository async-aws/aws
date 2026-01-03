<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Include or exclude RESOLUTION attribute for video in EXT-X-STREAM-INF tag of variant manifest.
 */
final class HlsStreamInfResolution
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
