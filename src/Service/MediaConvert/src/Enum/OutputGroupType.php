<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Type of output group (File group, Apple HLS, DASH ISO, Microsoft Smooth Streaming, CMAF).
 */
final class OutputGroupType
{
    public const CMAF_GROUP_SETTINGS = 'CMAF_GROUP_SETTINGS';
    public const DASH_ISO_GROUP_SETTINGS = 'DASH_ISO_GROUP_SETTINGS';
    public const FILE_GROUP_SETTINGS = 'FILE_GROUP_SETTINGS';
    public const HLS_GROUP_SETTINGS = 'HLS_GROUP_SETTINGS';
    public const MS_SMOOTH_GROUP_SETTINGS = 'MS_SMOOTH_GROUP_SETTINGS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CMAF_GROUP_SETTINGS => true,
            self::DASH_ISO_GROUP_SETTINGS => true,
            self::FILE_GROUP_SETTINGS => true,
            self::HLS_GROUP_SETTINGS => true,
            self::MS_SMOOTH_GROUP_SETTINGS => true,
        ][$value]);
    }
}
