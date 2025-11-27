<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether MediaConvert generates HLS manifests while your job is running or when your job is complete. To
 * generate HLS manifests while your job is running: Choose Enabled. Use if you want to play back your content as soon
 * as it's available. MediaConvert writes the parent and child manifests after the first three media segments are
 * written to your destination S3 bucket. It then writes new updated manifests after each additional segment is written.
 * The parent manifest includes the latest BANDWIDTH and AVERAGE-BANDWIDTH attributes, and child manifests include the
 * latest available media segment. When your job completes, the final child playlists include an EXT-X-ENDLIST tag. To
 * generate HLS manifests only when your job completes: Choose Disabled.
 */
final class HlsProgressiveWriteHlsManifest
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
