<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To include key-length-value metadata in this output: Set KLV metadata insertion to Passthrough. MediaConvert reads
 * KLV metadata present in your input and writes each instance to a separate event message box in the output, according
 * to MISB ST1910.1. To exclude this KLV metadata: Set KLV metadata insertion to None or leave blank.
 */
final class CmfcKlvMetadata
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
