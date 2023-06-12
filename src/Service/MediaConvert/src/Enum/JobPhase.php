<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * A job's phase can be PROBING, TRANSCODING OR UPLOADING.
 */
final class JobPhase
{
    public const PROBING = 'PROBING';
    public const TRANSCODING = 'TRANSCODING';
    public const UPLOADING = 'UPLOADING';

    public static function exists(string $value): bool
    {
        return isset([
            self::PROBING => true,
            self::TRANSCODING => true,
            self::UPLOADING => true,
        ][$value]);
    }
}
