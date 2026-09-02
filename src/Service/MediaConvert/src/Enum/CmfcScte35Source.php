<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless you have SCTE-35 markers in your input video file. Choose Passthrough if you want SCTE-35
 * markers that appear in your input to also appear in this output. Choose None if you don't want those SCTE-35 markers
 * in this output. When your input is an HLS manifest, choose Manifest cues to pass through CUE markers in your HLS
 * manifest as segment boundaries and SCTE-35 markers in this output at each EXT-X-CUE-OUT splice point in the input
 * manifest.
 */
final class CmfcScte35Source
{
    public const MANIFEST_CUES = 'MANIFEST_CUES';
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MANIFEST_CUES => true,
            self::NONE => true,
            self::PASSTHROUGH => true,
        ][$value]);
    }
}
