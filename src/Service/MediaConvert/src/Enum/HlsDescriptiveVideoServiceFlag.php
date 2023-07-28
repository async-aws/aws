<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether to flag this audio track as descriptive video service (DVS) in your HLS parent manifest. When you
 * choose Flag, MediaConvert includes the parameter CHARACTERISTICS="public.accessibility.describes-video" in the
 * EXT-X-MEDIA entry for this track. When you keep the default choice, Don't flag, MediaConvert leaves this parameter
 * out. The DVS flag can help with accessibility on Apple devices. For more information, see the Apple documentation.
 */
final class HlsDescriptiveVideoServiceFlag
{
    public const DONT_FLAG = 'DONT_FLAG';
    public const FLAG = 'FLAG';

    public static function exists(string $value): bool
    {
        return isset([
            self::DONT_FLAG => true,
            self::FLAG => true,
        ][$value]);
    }
}
