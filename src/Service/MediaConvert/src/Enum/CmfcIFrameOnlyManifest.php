<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose Include to have MediaConvert generate an HLS child manifest that lists only the I-frames for this rendition,
 * in addition to your regular manifest for this rendition. You might use this manifest as part of a workflow that
 * creates preview functions for your video. MediaConvert adds both the I-frame only child manifest and the regular
 * child manifest to the parent manifest. When you don't need the I-frame only child manifest, keep the default value
 * Exclude.
 */
final class CmfcIFrameOnlyManifest
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
