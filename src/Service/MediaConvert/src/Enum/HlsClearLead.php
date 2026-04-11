<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable Clear Lead DRM to reduce video startup latency by leaving the first segment unencrypted while DRM license
 * retrieval occurs in parallel. This optimization allows immediate playback startup while maintaining content
 * protection for the remainder of the stream. When enabled, the first output segment remains fully unencrypted, and
 * encryption begins at the start of the second segment. The HLS manifest will omit #EXT-X-KEY tags during the clear
 * segment and insert the first #EXT-X-KEY immediately before the first encrypted fragment. This feature is supported
 * exclusively for CMAF HLS (fMP4) outputs and is compatible with all existing key provider integrations (SPEKE v1,
 * SPEKE v2, and Static Key encryption). Supported codecs: H.264, H.265, and AV1 video codecs, and AAC audio codec.
 * Choose Enabled to activate Clear Lead DRM optimization. Choose Disabled to use standard encryption where all segments
 * are encrypted from the beginning.
 */
final class HlsClearLead
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
