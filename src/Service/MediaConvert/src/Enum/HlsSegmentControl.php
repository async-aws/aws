<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to SINGLE_FILE, emits program as a single media resource (.ts) file, uses #EXT-X-BYTERANGE tags to index
 * segment for playback.
 */
final class HlsSegmentControl
{
    public const SEGMENTED_FILES = 'SEGMENTED_FILES';
    public const SINGLE_FILE = 'SINGLE_FILE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SEGMENTED_FILES => true,
            self::SINGLE_FILE => true,
        ][$value]);
    }
}
