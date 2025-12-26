<?php

namespace AsyncAws\Lambda\Enum;

final class LogFormat
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const JSON = 'JSON';
    public const TEXT = 'Text';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::JSON => true,
            self::TEXT => true,
        ][$value]);
    }
}
