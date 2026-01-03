<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the MXF profile, also called shim, for this output. To automatically select a profile according to your
 * output video codec and resolution, leave blank. For a list of codecs supported with each MXF profile, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/codecs-supported-with-each-mxf-profile.html. For more information
 * about the automatic selection behavior, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/default-automatic-selection-of-mxf-profiles.html.
 */
final class MxfProfile
{
    public const D_10 = 'D_10';
    public const OP1A = 'OP1A';
    public const XAVC = 'XAVC';
    public const XDCAM = 'XDCAM';
    public const XDCAM_RDD9 = 'XDCAM_RDD9';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::D_10 => true,
            self::OP1A => true,
            self::XAVC => true,
            self::XDCAM => true,
            self::XDCAM_RDD9 => true,
        ][$value]);
    }
}
