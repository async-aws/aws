<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how the transcoder determines GOP size for this output. We recommend that you have the transcoder
 * automatically choose this value for you based on characteristics of your input video. To enable this automatic
 * behavior, choose Auto and and leave GOP size blank. By default, if you don't specify GOP mode control, MediaConvert
 * will use automatic behavior. If your output group specifies HLS, DASH, or CMAF, set GOP mode control to Auto and
 * leave GOP size blank in each output in your output group. To explicitly specify the GOP length, choose Specified,
 * frames or Specified, seconds and then provide the GOP length in the related setting GOP size.
 */
final class H264GopSizeUnits
{
    public const AUTO = 'AUTO';
    public const FRAMES = 'FRAMES';
    public const SECONDS = 'SECONDS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::FRAMES => true,
            self::SECONDS => true,
        ][$value]);
    }
}
