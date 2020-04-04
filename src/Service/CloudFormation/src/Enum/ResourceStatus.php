<?php

namespace AsyncAws\CloudFormation\Enum;

final class ResourceStatus
{
    public const CREATE_COMPLETE = 'CREATE_COMPLETE';
    public const CREATE_FAILED = 'CREATE_FAILED';
    public const CREATE_IN_PROGRESS = 'CREATE_IN_PROGRESS';
    public const DELETE_COMPLETE = 'DELETE_COMPLETE';
    public const DELETE_FAILED = 'DELETE_FAILED';
    public const DELETE_IN_PROGRESS = 'DELETE_IN_PROGRESS';
    public const DELETE_SKIPPED = 'DELETE_SKIPPED';
    public const IMPORT_COMPLETE = 'IMPORT_COMPLETE';
    public const IMPORT_FAILED = 'IMPORT_FAILED';
    public const IMPORT_IN_PROGRESS = 'IMPORT_IN_PROGRESS';
    public const IMPORT_ROLLBACK_COMPLETE = 'IMPORT_ROLLBACK_COMPLETE';
    public const IMPORT_ROLLBACK_FAILED = 'IMPORT_ROLLBACK_FAILED';
    public const IMPORT_ROLLBACK_IN_PROGRESS = 'IMPORT_ROLLBACK_IN_PROGRESS';
    public const UPDATE_COMPLETE = 'UPDATE_COMPLETE';
    public const UPDATE_FAILED = 'UPDATE_FAILED';
    public const UPDATE_IN_PROGRESS = 'UPDATE_IN_PROGRESS';

    public static function exists(string $value): bool
    {
        return isset([
            self::CREATE_COMPLETE => true,
            self::CREATE_FAILED => true,
            self::CREATE_IN_PROGRESS => true,
            self::DELETE_COMPLETE => true,
            self::DELETE_FAILED => true,
            self::DELETE_IN_PROGRESS => true,
            self::DELETE_SKIPPED => true,
            self::IMPORT_COMPLETE => true,
            self::IMPORT_FAILED => true,
            self::IMPORT_IN_PROGRESS => true,
            self::IMPORT_ROLLBACK_COMPLETE => true,
            self::IMPORT_ROLLBACK_FAILED => true,
            self::IMPORT_ROLLBACK_IN_PROGRESS => true,
            self::UPDATE_COMPLETE => true,
            self::UPDATE_FAILED => true,
            self::UPDATE_IN_PROGRESS => true,
        ][$value]);
    }
}
