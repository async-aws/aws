<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the type of the ID3 frame (timedMetadataId3Frame) to use for ID3 timestamps (timedMetadataId3Period) in your
 * output. To include ID3 timestamps: Specify PRIV (PRIV) or TDRL (TDRL) and set ID3 metadata (timedMetadata) to
 * Passthrough (PASSTHROUGH). To exclude ID3 timestamps: Set ID3 timestamp frame type to None (NONE).
 */
final class HlsTimedMetadataId3Frame
{
    public const NONE = 'NONE';
    public const PRIV = 'PRIV';
    public const TDRL = 'TDRL';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PRIV => true,
            self::TDRL => true,
        ][$value]);
    }
}
