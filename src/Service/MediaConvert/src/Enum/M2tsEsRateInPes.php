<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Controls whether to include the ES Rate field in the PES header.
 */
final class M2tsEsRateInPes
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
