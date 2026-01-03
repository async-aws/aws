<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set ID3 metadata to Passthrough to include ID3 metadata in this output. This includes ID3 metadata from the following
 * features: ID3 timestamp period, and Custom ID3 metadata inserter. To exclude this ID3 metadata in this output: set
 * ID3 metadata to None or leave blank.
 */
final class TimedMetadata
{
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PASSTHROUGH => true,
        ][$value]);
    }
}
