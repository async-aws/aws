<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Controls whether to include the ES Rate field in the PES header.
 */
final class M2tsEsRateInPes
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
