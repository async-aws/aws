<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * To include key-length-value metadata in this output: Set KLV metadata insertion to Passthrough. MediaConvert reads
 * KLV metadata present in your input and passes it through to the output transport stream. To exclude this KLV
 * metadata: Set KLV metadata insertion to None or leave blank.
 */
final class M2tsKlvMetadata
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
