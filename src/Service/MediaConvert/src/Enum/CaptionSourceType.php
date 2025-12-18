<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Source to identify the format of your input captions. The service cannot auto-detect caption format.
 */
final class CaptionSourceType
{
    public const ANCILLARY = 'ANCILLARY';
    public const DVB_SUB = 'DVB_SUB';
    public const EMBEDDED = 'EMBEDDED';
    public const IMSC = 'IMSC';
    public const NULL_SOURCE = 'NULL_SOURCE';
    public const SCC = 'SCC';
    public const SCTE20 = 'SCTE20';
    public const SMI = 'SMI';
    public const SMPTE_TT = 'SMPTE_TT';
    public const SRT = 'SRT';
    public const STL = 'STL';
    public const TELETEXT = 'TELETEXT';
    public const TTML = 'TTML';
    public const TT_3GPP = 'TT_3GPP';
    public const WEBVTT = 'WEBVTT';

    public static function exists(string $value): bool
    {
        return isset([
            self::ANCILLARY => true,
            self::DVB_SUB => true,
            self::EMBEDDED => true,
            self::IMSC => true,
            self::NULL_SOURCE => true,
            self::SCC => true,
            self::SCTE20 => true,
            self::SMI => true,
            self::SMPTE_TT => true,
            self::SRT => true,
            self::STL => true,
            self::TELETEXT => true,
            self::TTML => true,
            self::TT_3GPP => true,
            self::WEBVTT => true,
        ][$value]);
    }
}
