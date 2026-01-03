<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Indicates whether the .m3u8 manifest file should be generated for this HLS output group.
 */
final class HlsOutputSelection
{
    public const MANIFESTS_AND_SEGMENTS = 'MANIFESTS_AND_SEGMENTS';
    public const SEGMENTS_ONLY = 'SEGMENTS_ONLY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MANIFESTS_AND_SEGMENTS => true,
            self::SEGMENTS_ONLY => true,
        ][$value]);
    }
}
