<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Include or exclude RESOLUTION attribute for video in EXT-X-STREAM-INF tag of variant manifest.
 */
final class CmafStreamInfResolution
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
