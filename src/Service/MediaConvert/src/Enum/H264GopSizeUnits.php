<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how the transcoder determines GOP size for this output. We recommend that you have the transcoder
 * automatically choose this value for you based on characteristics of your input video. To enable this automatic
 * behavior, choose Auto (AUTO) and and leave GOP size (GopSize) blank. By default, if you don't specify GOP mode
 * control (GopSizeUnits), MediaConvert will use automatic behavior. If your output group specifies HLS, DASH, or CMAF,
 * set GOP mode control to Auto and leave GOP size blank in each output in your output group. To explicitly specify the
 * GOP length, choose Specified, frames (FRAMES) or Specified, seconds (SECONDS) and then provide the GOP length in the
 * related setting GOP size (GopSize).
 */
final class H264GopSizeUnits
{
    public const AUTO = 'AUTO';
    public const FRAMES = 'FRAMES';
    public const SECONDS = 'SECONDS';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::FRAMES => true,
            self::SECONDS => true,
        ][$value]);
    }
}
