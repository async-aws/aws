<?php

namespace AsyncAws\CodeCommit\Enum;

final class RepositoryTriggerEventEnum
{
    public const ALL = 'all';
    public const CREATE_REFERENCE = 'createReference';
    public const DELETE_REFERENCE = 'deleteReference';
    public const UPDATE_REFERENCE = 'updateReference';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ALL => true,
            self::CREATE_REFERENCE => true,
            self::DELETE_REFERENCE => true,
            self::UPDATE_REFERENCE => true,
        ][$value]);
    }
}
