<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether to pass SMPTE 337M-wrapped audio (such as Dolby E) through without unwrapping. Choose Enabled to pass
 * the SMPTE 337M container through unchanged, treating the track as raw PCM. Choose Disabled (default) to automatically
 * detect and unwrap SMPTE 337M data, extracting the underlying Dolby E programs as separate audio tracks for encoding.
 * When this field is absent, the service defaults to Disabled (auto-unwrap).
 */
final class AudioSmpte337Passthrough
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
