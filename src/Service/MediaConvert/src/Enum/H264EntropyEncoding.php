<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Entropy encoding mode. Use CABAC (must be in Main or High profile) or CAVLC.
 */
final class H264EntropyEncoding
{
    public const CABAC = 'CABAC';
    public const CAVLC = 'CAVLC';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CABAC => true,
            self::CAVLC => true,
        ][$value]);
    }
}
