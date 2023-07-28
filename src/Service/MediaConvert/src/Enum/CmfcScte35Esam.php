<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting only when you specify SCTE-35 markers from ESAM. Choose INSERT to put SCTE-35 markers in this output
 * at the insertion points that you specify in an ESAM XML document. Provide the document in the setting SCC XML.
 */
final class CmfcScte35Esam
{
    public const INSERT = 'INSERT';
    public const NONE = 'NONE';

    public static function exists(string $value): bool
    {
        return isset([
            self::INSERT => true,
            self::NONE => true,
        ][$value]);
    }
}
