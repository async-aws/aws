<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the event message box (eMSG) version for ID3 timed metadata in your output.
 * For more information, see ISO/IEC 23009-1:2022 section 5.10.3.3.3 Syntax.
 * Leave blank to use the default value Version 0.
 * When you specify Version 1, you must also set ID3 metadata to Passthrough.
 */
final class CmfcTimedMetadataBoxVersion
{
    public const VERSION_0 = 'VERSION_0';
    public const VERSION_1 = 'VERSION_1';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::VERSION_0 => true,
            self::VERSION_1 => true,
        ][$value]);
    }
}
