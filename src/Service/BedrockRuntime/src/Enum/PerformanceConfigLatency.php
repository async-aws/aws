<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class PerformanceConfigLatency
{
    public const OPTIMIZED = 'optimized';
    public const STANDARD = 'standard';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::OPTIMIZED => true,
            self::STANDARD => true,
        ][$value]);
    }
}
