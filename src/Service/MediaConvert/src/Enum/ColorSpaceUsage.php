<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * There are two sources for color metadata, the input file and the job input settings Color space and HDR master
 * display information settings. The Color space usage setting determines which takes precedence. Choose Force to use
 * color metadata from the input job settings. If you don't specify values for those settings, the service defaults to
 * using metadata from your input. FALLBACK - Choose Fallback to use color metadata from the source when it is present.
 * If there's no color metadata in your input file, the service defaults to using values you specify in the input
 * settings.
 */
final class ColorSpaceUsage
{
    public const FALLBACK = 'FALLBACK';
    public const FORCE = 'FORCE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FALLBACK => true,
            self::FORCE => true,
        ][$value]);
    }
}
