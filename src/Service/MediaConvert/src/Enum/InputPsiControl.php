<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set PSI control for transport stream inputs to specify which data the demux process to scans.
 * * Ignore PSI - Scan all PIDs for audio and video.
 * * Use PSI - Scan only PSI data.
 */
final class InputPsiControl
{
    public const IGNORE_PSI = 'IGNORE_PSI';
    public const USE_PSI = 'USE_PSI';

    public static function exists(string $value): bool
    {
        return isset([
            self::IGNORE_PSI => true,
            self::USE_PSI => true,
        ][$value]);
    }
}
