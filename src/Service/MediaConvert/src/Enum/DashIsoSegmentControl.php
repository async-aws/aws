<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When set to SINGLE_FILE, a single output file is generated, which is internally segmented using the Fragment Length
 * and Segment Length. When set to SEGMENTED_FILES, separate segment files will be created.
 */
final class DashIsoSegmentControl
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
