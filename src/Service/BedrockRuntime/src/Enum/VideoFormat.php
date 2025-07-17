<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class VideoFormat
{
    public const FLV = 'flv';
    public const MKV = 'mkv';
    public const MOV = 'mov';
    public const MPEG = 'mpeg';
    public const MPG = 'mpg';
    public const MP_4 = 'mp4';
    public const THREE_GP = 'three_gp';
    public const WEBM = 'webm';
    public const WMV = 'wmv';

    public static function exists(string $value): bool
    {
        return isset([
            self::FLV => true,
            self::MKV => true,
            self::MOV => true,
            self::MPEG => true,
            self::MPG => true,
            self::MP_4 => true,
            self::THREE_GP => true,
            self::WEBM => true,
            self::WMV => true,
        ][$value]);
    }
}
