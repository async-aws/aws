<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Indicates whether segments should be placed in subdirectories.
 */
final class HlsDirectoryStructure
{
    public const SINGLE_DIRECTORY = 'SINGLE_DIRECTORY';
    public const SUBDIRECTORY_PER_STREAM = 'SUBDIRECTORY_PER_STREAM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SINGLE_DIRECTORY => true,
            self::SUBDIRECTORY_PER_STREAM => true,
        ][$value]);
    }
}
