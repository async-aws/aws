<?php

namespace AsyncAws\SecretsManager\Enum;

/**
 * Filters your list of secrets by a specific key.
 */
final class FilterNameStringType
{
    public const ALL = 'all';
    public const DESCRIPTION = 'description';
    public const NAME = 'name';
    public const PRIMARY_REGION = 'primary-region';
    public const TAG_KEY = 'tag-key';
    public const TAG_VALUE = 'tag-value';

    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::DESCRIPTION => true,
            self::NAME => true,
            self::PRIMARY_REGION => true,
            self::TAG_KEY => true,
            self::TAG_VALUE => true,
        ][$value]);
    }
}
