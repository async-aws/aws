<?php

namespace AsyncAws\SecretsManager\Enum;

/**
 * The following are keys you can use:.
 *
 * - **description**: Prefix match, not case-sensitive.
 * - **name**: Prefix match, case-sensitive.
 * - **tag-key**: Prefix match, case-sensitive.
 * - **tag-value**: Prefix match, case-sensitive.
 * - **primary-region**: Prefix match, case-sensitive.
 * - **all**: Breaks the filter value string into words and then searches all attributes for matches. Not
 *   case-sensitive.
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
