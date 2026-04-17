<?php

namespace AsyncAws\ImageBuilder\Enum;

final class TenancyType
{
    public const DEDICATED = 'dedicated';
    public const DEFAULT = 'default';
    public const HOST = 'host';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEDICATED => true,
            self::DEFAULT => true,
            self::HOST => true,
        ][$value]);
    }
}
