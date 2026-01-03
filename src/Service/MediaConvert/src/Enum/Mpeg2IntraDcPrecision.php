<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use Intra DC precision to set quantization precision for intra-block DC coefficients. If you choose the value auto,
 * the service will automatically select the precision based on the per-frame compression ratio.
 */
final class Mpeg2IntraDcPrecision
{
    public const AUTO = 'AUTO';
    public const INTRA_DC_PRECISION_10 = 'INTRA_DC_PRECISION_10';
    public const INTRA_DC_PRECISION_11 = 'INTRA_DC_PRECISION_11';
    public const INTRA_DC_PRECISION_8 = 'INTRA_DC_PRECISION_8';
    public const INTRA_DC_PRECISION_9 = 'INTRA_DC_PRECISION_9';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::INTRA_DC_PRECISION_10 => true,
            self::INTRA_DC_PRECISION_11 => true,
            self::INTRA_DC_PRECISION_8 => true,
            self::INTRA_DC_PRECISION_9 => true,
        ][$value]);
    }
}
