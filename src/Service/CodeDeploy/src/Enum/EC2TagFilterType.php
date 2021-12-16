<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * The tag filter type:.
 *
 * - `KEY_ONLY`: Key only.
 * - `VALUE_ONLY`: Value only.
 * - `KEY_AND_VALUE`: Key and value.
 */
final class EC2TagFilterType
{
    public const KEY_AND_VALUE = 'KEY_AND_VALUE';
    public const KEY_ONLY = 'KEY_ONLY';
    public const VALUE_ONLY = 'VALUE_ONLY';

    public static function exists(string $value): bool
    {
        return isset([
            self::KEY_AND_VALUE => true,
            self::KEY_ONLY => true,
            self::VALUE_ONLY => true,
        ][$value]);
    }
}
