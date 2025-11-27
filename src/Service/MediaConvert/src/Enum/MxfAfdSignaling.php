<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional. When you have AFD signaling set up in your output video stream, use this setting to choose whether to also
 * include it in the MXF wrapper. Choose Don't copy to exclude AFD signaling from the MXF wrapper. Choose Copy from
 * video stream to copy the AFD values from the video stream for this output to the MXF wrapper. Regardless of which
 * option you choose, the AFD values remain in the video stream. Related settings: To set up your output to include or
 * exclude AFD values, see AfdSignaling, under VideoDescription. On the console, find AFD signaling under the output's
 * video encoding settings.
 */
final class MxfAfdSignaling
{
    public const COPY_FROM_VIDEO = 'COPY_FROM_VIDEO';
    public const NO_COPY = 'NO_COPY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COPY_FROM_VIDEO => true,
            self::NO_COPY => true,
        ][$value]);
    }
}
