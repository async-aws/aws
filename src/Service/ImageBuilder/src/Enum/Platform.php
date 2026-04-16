<?php

namespace AsyncAws\ImageBuilder\Enum;

final class Platform
{
    public const LINUX = 'Linux';
    public const MAC_OS = 'macOS';
    public const WINDOWS = 'Windows';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LINUX => true,
            self::MAC_OS => true,
            self::WINDOWS => true,
        ][$value]);
    }
}
