<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class PerformanceConfigLatency
{
    public const OPTIMIZED = 'optimized';
    public const STANDARD = 'standard';

    public static function exists(string $value): bool
    {
        return isset([
            self::OPTIMIZED => true,
            self::STANDARD => true,
        ][$value]);
    }
}
