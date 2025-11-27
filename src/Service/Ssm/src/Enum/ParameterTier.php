<?php

namespace AsyncAws\Ssm\Enum;

final class ParameterTier
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const ADVANCED = 'Advanced';
    public const INTELLIGENT_TIERING = 'Intelligent-Tiering';
    public const STANDARD = 'Standard';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ADVANCED => true,
            self::INTELLIGENT_TIERING => true,
            self::STANDARD => true,
        ][$value]);
    }
}
