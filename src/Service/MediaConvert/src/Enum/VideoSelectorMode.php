<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * AUTO will select the highest bitrate input in the video selector source. REMUX_ALL will passthrough all the selected
 * streams in the video selector source. When selecting streams from multiple renditions (i.e. using Stream video
 * selector type): REMUX_ALL will only remux all streams selected, and AUTO will use the highest bitrate video stream
 * among the selected streams as source.
 */
final class VideoSelectorMode
{
    public const AUTO = 'AUTO';
    public const REMUX_ALL = 'REMUX_ALL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::REMUX_ALL => true,
        ][$value]);
    }
}
