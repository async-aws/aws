<?php

namespace AsyncAws\Kms\Enum;

final class EncapsulationAlgorithmSpec
{
    public const ML_KEM = 'ML_KEM';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ML_KEM => true,
        ][$value]);
    }
}
