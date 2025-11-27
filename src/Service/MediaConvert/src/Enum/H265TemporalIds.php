<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enables temporal layer identifiers in the encoded bitstream. Up to 3 layers are supported depending on GOP structure:
 * I- and P-frames form one layer, reference B-frames can form a second layer and non-reference b-frames can form a
 * third layer. Decoders can optionally decode only the lower temporal layers to generate a lower frame rate output. For
 * example, given a bitstream with temporal IDs and with b-frames = 1 (i.e. IbPbPb display order), a decoder could
 * decode all the frames for full frame rate output or only the I and P frames (lowest temporal layer) for a half frame
 * rate output.
 */
final class H265TemporalIds
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
