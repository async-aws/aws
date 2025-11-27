<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The video encoding method for your MPEG-4 AVC output. Keep the default value, PAFF, to have MediaConvert use PAFF
 * encoding for interlaced outputs. Choose Force field to disable PAFF encoding and create separate interlaced fields.
 * Choose MBAFF to disable PAFF and have MediaConvert use MBAFF encoding for interlaced outputs.
 */
final class H264FieldEncoding
{
    public const FORCE_FIELD = 'FORCE_FIELD';
    public const MBAFF = 'MBAFF';
    public const PAFF = 'PAFF';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FORCE_FIELD => true,
            self::MBAFF => true,
            self::PAFF => true,
        ][$value]);
    }
}
