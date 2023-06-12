<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * For SCTE-35 markers from your input-- Choose Passthrough (PASSTHROUGH) if you want SCTE-35 markers that appear in
 * your input to also appear in this output. Choose None (NONE) if you don't want SCTE-35 markers in this output. For
 * SCTE-35 markers from an ESAM XML document-- Choose None (NONE). Also provide the ESAM XML as a string in the setting
 * Signal processing notification XML (sccXml). Also enable ESAM SCTE-35 (include the property scte35Esam).
 */
final class M2tsScte35Source
{
    public const NONE = 'NONE';
    public const PASSTHROUGH = 'PASSTHROUGH';

    public static function exists(string $value): bool
    {
        return isset([
            self::NONE => true,
            self::PASSTHROUGH => true,
        ][$value]);
    }
}
