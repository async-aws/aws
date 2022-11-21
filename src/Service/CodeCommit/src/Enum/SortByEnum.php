<?php

namespace AsyncAws\CodeCommit\Enum;

/**
 * The criteria used to sort the results of a list repositories operation.
 */
final class SortByEnum
{
    public const LAST_MODIFIED_DATE = 'lastModifiedDate';
    public const REPOSITORY_NAME = 'repositoryName';

    public static function exists(string $value): bool
    {
        return isset([
            self::LAST_MODIFIED_DATE => true,
            self::REPOSITORY_NAME => true,
        ][$value]);
    }
}
