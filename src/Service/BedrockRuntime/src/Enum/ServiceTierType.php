<?php

namespace AsyncAws\BedrockRuntime\Enum;

final class ServiceTierType
{
    public const DEFAULT = 'default';
    public const FLEX = 'flex';
    public const PRIORITY = 'priority';
    public const RESERVED = 'reserved';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::FLEX => true,
            self::PRIORITY => true,
            self::RESERVED => true,
        ][$value]);
    }
}
