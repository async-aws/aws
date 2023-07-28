<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If you select ALIGN_TO_VIDEO, MediaConvert writes captions and data packets with Presentation Timestamp (PTS) values
 * greater than or equal to the first video packet PTS (MediaConvert drops captions and data packets with lesser PTS
 * values). Keep the default value to allow all PTS values.
 */
final class M2tsDataPtsControl
{
    public const ALIGN_TO_VIDEO = 'ALIGN_TO_VIDEO';
    public const AUTO = 'AUTO';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALIGN_TO_VIDEO => true,
            self::AUTO => true,
        ][$value]);
    }
}
