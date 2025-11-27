<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the video selector type for your HLS input. Use to specify which video rendition MediaConvert uses from your
 * HLS input. To have MediaConvert automatically use the highest bitrate rendition from your HLS input: Keep the default
 * value, Auto. To manually specify a rendition: Choose Stream. Then enter the unique stream number in the Streams
 * array, starting at 1, corresponding to the stream order in the manifest.
 */
final class VideoSelectorType
{
    public const AUTO = 'AUTO';
    public const STREAM = 'STREAM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::STREAM => true,
        ][$value]);
    }
}
