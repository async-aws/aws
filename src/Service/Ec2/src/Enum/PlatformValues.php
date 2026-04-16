<?php

namespace AsyncAws\Ec2\Enum;

final class PlatformValues
{
    public const WINDOWS = 'Windows';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::WINDOWS => true,
        ][$value]);
    }
}
