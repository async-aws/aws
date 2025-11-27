<?php

namespace AsyncAws\CloudFormation\Enum;

final class OperationType
{
    public const CONTINUE_ROLLBACK = 'CONTINUE_ROLLBACK';
    public const CREATE_CHANGESET = 'CREATE_CHANGESET';
    public const CREATE_STACK = 'CREATE_STACK';
    public const DELETE_STACK = 'DELETE_STACK';
    public const ROLLBACK = 'ROLLBACK';
    public const UPDATE_STACK = 'UPDATE_STACK';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CONTINUE_ROLLBACK => true,
            self::CREATE_CHANGESET => true,
            self::CREATE_STACK => true,
            self::DELETE_STACK => true,
            self::ROLLBACK => true,
            self::UPDATE_STACK => true,
        ][$value]);
    }
}
