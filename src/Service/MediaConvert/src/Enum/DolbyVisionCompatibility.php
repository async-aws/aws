<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When you set Compatibility mapping to Duplicate Stream, DolbyVision streams that have a backward compatible base
 * layer (e.g., DolbyVision 8.1) will cause a duplicate stream to be signaled in the manifest as a duplicate stream.
 * When you set Compatibility mapping to Supplemntal Codecs, DolbyVision streams that have a backward compatible base
 * layer (e.g., DolbyVision 8.1) will cause the associate stream in the manifest to include a SUPPLEMENTAL_CODECS
 * property.
 */
final class DolbyVisionCompatibility
{
    public const DUPLICATE_STREAM = 'DUPLICATE_STREAM';
    public const SUPPLEMENTAL_CODECS = 'SUPPLEMENTAL_CODECS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DUPLICATE_STREAM => true,
            self::SUPPLEMENTAL_CODECS => true,
        ][$value]);
    }
}
