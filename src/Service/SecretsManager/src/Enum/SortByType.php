<?php

namespace AsyncAws\SecretsManager\Enum;

final class SortByType
{
    public const CREATED_DATE = 'created-date';
    public const LAST_ACCESSED_DATE = 'last-accessed-date';
    public const LAST_CHANGED_DATE = 'last-changed-date';
    public const NAME = 'name';

    public static function exists(string $value): bool
    {
        return isset([
            self::CREATED_DATE => true,
            self::LAST_ACCESSED_DATE => true,
            self::LAST_CHANGED_DATE => true,
            self::NAME => true,
        ][$value]);
    }
}
