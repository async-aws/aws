<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify whether MediaConvert generates images for trick play. Keep the default value, None, to not generate any
 * images. Choose Thumbnail to generate tiled thumbnails. Choose Thumbnail and full frame to generate tiled thumbnails
 * and full-resolution images of single frames. When you enable Write HLS manifest, MediaConvert creates a child
 * manifest for each set of images that you generate and adds corresponding entries to the parent manifest. When you
 * enable Write DASH manifest, MediaConvert adds an entry in the .mpd manifest for each set of images that you generate.
 * A common application for these images is Roku trick mode. The thumbnails and full-frame images that MediaConvert
 * creates with this feature are compatible with this Roku specification:
 * https://developer.roku.com/docs/developer-program/media-playback/trick-mode/hls-and-dash.md.
 */
final class CmafImageBasedTrickPlay
{
    public const ADVANCED = 'ADVANCED';
    public const NONE = 'NONE';
    public const THUMBNAIL = 'THUMBNAIL';
    public const THUMBNAIL_AND_FULLFRAME = 'THUMBNAIL_AND_FULLFRAME';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ADVANCED => true,
            self::NONE => true,
            self::THUMBNAIL => true,
            self::THUMBNAIL_AND_FULLFRAME => true,
        ][$value]);
    }
}
