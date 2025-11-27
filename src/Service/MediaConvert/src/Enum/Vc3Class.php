<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the VC3 class to choose the quality characteristics for this output. VC3 class, together with the settings
 * Framerate (framerateNumerator and framerateDenominator) and Resolution (height and width), determine your output
 * bitrate. For example, say that your video resolution is 1920x1080 and your framerate is 29.97. Then Class 145 gives
 * you an output with a bitrate of approximately 145 Mbps and Class 220 gives you and output with a bitrate of
 * approximately 220 Mbps. VC3 class also specifies the color bit depth of your output.
 */
final class Vc3Class
{
    public const CLASS_145_8BIT = 'CLASS_145_8BIT';
    public const CLASS_220_10BIT = 'CLASS_220_10BIT';
    public const CLASS_220_8BIT = 'CLASS_220_8BIT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CLASS_145_8BIT => true,
            self::CLASS_220_10BIT => true,
            self::CLASS_220_8BIT => true,
        ][$value]);
    }
}
