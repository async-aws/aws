<?php

namespace AsyncAws\CodeDeploy\Enum;

final class EC2TagFilterType
{
    public const KEY_AND_VALUE = 'KEY_AND_VALUE';
    public const KEY_ONLY = 'KEY_ONLY';
    public const VALUE_ONLY = 'VALUE_ONLY';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KEY_AND_VALUE => true,
            self::KEY_ONLY => true,
            self::VALUE_ONLY => true,
        ][$value]);
    }
}
