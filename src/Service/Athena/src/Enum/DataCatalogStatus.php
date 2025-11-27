<?php

namespace AsyncAws\Athena\Enum;

final class DataCatalogStatus
{
    public const CREATE_COMPLETE = 'CREATE_COMPLETE';
    public const CREATE_FAILED = 'CREATE_FAILED';
    public const CREATE_FAILED_CLEANUP_COMPLETE = 'CREATE_FAILED_CLEANUP_COMPLETE';
    public const CREATE_FAILED_CLEANUP_FAILED = 'CREATE_FAILED_CLEANUP_FAILED';
    public const CREATE_FAILED_CLEANUP_IN_PROGRESS = 'CREATE_FAILED_CLEANUP_IN_PROGRESS';
    public const CREATE_IN_PROGRESS = 'CREATE_IN_PROGRESS';
    public const DELETE_COMPLETE = 'DELETE_COMPLETE';
    public const DELETE_FAILED = 'DELETE_FAILED';
    public const DELETE_IN_PROGRESS = 'DELETE_IN_PROGRESS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CREATE_COMPLETE => true,
            self::CREATE_FAILED => true,
            self::CREATE_FAILED_CLEANUP_COMPLETE => true,
            self::CREATE_FAILED_CLEANUP_FAILED => true,
            self::CREATE_FAILED_CLEANUP_IN_PROGRESS => true,
            self::CREATE_IN_PROGRESS => true,
            self::DELETE_COMPLETE => true,
            self::DELETE_FAILED => true,
            self::DELETE_IN_PROGRESS => true,
        ][$value]);
    }
}
