<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the type of the ID3 frame to use for ID3 timestamps in your output. To include ID3 timestamps: Specify PRIV
 * or TDRL and set ID3 metadata to Passthrough. To exclude ID3 timestamps: Set ID3 timestamp frame type to None.
 */
final class HlsTimedMetadataId3Frame
{
    public const NONE = 'NONE';
    public const PRIV = 'PRIV';
    public const TDRL = 'TDRL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PRIV => true,
            self::TDRL => true,
        ][$value]);
    }
}
