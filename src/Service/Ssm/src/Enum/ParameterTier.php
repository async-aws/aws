<?php

namespace AsyncAws\Ssm\Enum;

final class ParameterTier
{
    public const ADVANCED = 'Advanced';
    public const INTELLIGENT_TIERING = 'Intelligent-Tiering';
    public const STANDARD = 'Standard';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

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
