<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the format for this set of captions on this output. The default format is embedded without SCTE-20. Note that
 * your choice of video output container constrains your choice of output captions format. For more information, see
 * https://docs.aws.amazon.com/mediaconvert/latest/ug/captions-support-tables.html. If you are using SCTE-20 and you
 * want to create an output that complies with the SCTE-43 spec, choose SCTE-20 plus embedded. To create a non-compliant
 * output where the embedded captions come first, choose Embedded plus SCTE-20.
 */
final class CaptionDestinationType
{
    public const BURN_IN = 'BURN_IN';
    public const DVB_SUB = 'DVB_SUB';
    public const EMBEDDED = 'EMBEDDED';
    public const EMBEDDED_PLUS_SCTE20 = 'EMBEDDED_PLUS_SCTE20';
    public const IMSC = 'IMSC';
    public const SCC = 'SCC';
    public const SCTE20_PLUS_EMBEDDED = 'SCTE20_PLUS_EMBEDDED';
    public const SMI = 'SMI';
    public const SRT = 'SRT';
    public const TELETEXT = 'TELETEXT';
    public const TTML = 'TTML';
    public const WEBVTT = 'WEBVTT';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BURN_IN => true,
            self::DVB_SUB => true,
            self::EMBEDDED => true,
            self::EMBEDDED_PLUS_SCTE20 => true,
            self::IMSC => true,
            self::SCC => true,
            self::SCTE20_PLUS_EMBEDDED => true,
            self::SMI => true,
            self::SRT => true,
            self::TELETEXT => true,
            self::TTML => true,
            self::WEBVTT => true,
        ][$value]);
    }
}
