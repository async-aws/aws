<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set ID3 metadata (timedMetadata) to Passthrough (PASSTHROUGH) to include ID3 metadata in this output. This includes
 * ID3 metadata from the following features: ID3 timestamp period (timedMetadataId3Period), and Custom ID3 metadata
 * inserter (timedMetadataInsertion). To exclude this ID3 metadata in this output: set ID3 metadata to None (NONE) or
 * leave blank.
 */
final class TimedMetadata
{
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PASSTHROUGH => true,
        ][$value]);
    }
}
