<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Container for this output. Some containers require a container settings object. If not specified, the default object
 * will be created.
 */
final class ContainerType
{
    public const CMFC = 'CMFC';
    public const F4V = 'F4V';
    public const GIF = 'GIF';
    public const ISMV = 'ISMV';
    public const M2TS = 'M2TS';
    public const M3U8 = 'M3U8';
    public const MOV = 'MOV';
    public const MP4 = 'MP4';
    public const MPD = 'MPD';
    public const MXF = 'MXF';
    public const OGG = 'OGG';
    public const RAW = 'RAW';
    public const WEBM = 'WEBM';
    public const Y4M = 'Y4M';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CMFC => true,
            self::F4V => true,
            self::GIF => true,
            self::ISMV => true,
            self::M2TS => true,
            self::M3U8 => true,
            self::MOV => true,
            self::MP4 => true,
            self::MPD => true,
            self::MXF => true,
            self::OGG => true,
            self::RAW => true,
            self::WEBM => true,
            self::Y4M => true,
        ][$value]);
    }
}
