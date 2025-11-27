<?php

namespace AsyncAws\CloudFormation\Enum;

final class DeletionMode
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const FORCE_DELETE_STACK = 'FORCE_DELETE_STACK';
    public const STANDARD = 'STANDARD';

    public static function exists(string $value): bool
    {
        return isset([
            self::FORCE_DELETE_STACK => true,
            self::STANDARD => true,
        ][$value]);
    }
}
