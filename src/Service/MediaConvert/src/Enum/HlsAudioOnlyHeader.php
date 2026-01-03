<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Ignore this setting unless you are using FairPlay DRM with Verimatrix and you encounter playback issues. Keep the
 * default value, Include, to output audio-only headers. Choose Exclude to remove the audio-only headers from your audio
 * segments.
 */
final class HlsAudioOnlyHeader
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
