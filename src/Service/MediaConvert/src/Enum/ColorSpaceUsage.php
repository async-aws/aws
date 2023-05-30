<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * There are two sources for color metadata, the input file and the job input settings Color space (ColorSpace) and HDR
 * master display information settings(Hdr10Metadata). The Color space usage setting determines which takes precedence.
 * Choose Force (FORCE) to use color metadata from the input job settings. If you don't specify values for those
 * settings, the service defaults to using metadata from your input. FALLBACK - Choose Fallback (FALLBACK) to use color
 * metadata from the source when it is present. If there's no color metadata in your input file, the service defaults to
 * using values you specify in the input settings.
 */
final class ColorSpaceUsage
{
    public const FALLBACK = 'FALLBACK';
    public const FORCE = 'FORCE';

    public static function exists(string $value): bool
    {
        return isset([
            self::FALLBACK => true,
            self::FORCE => true,
        ][$value]);
    }
}
