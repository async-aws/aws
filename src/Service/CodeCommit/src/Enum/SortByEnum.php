<?php

namespace AsyncAws\CodeCommit\Enum;

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
