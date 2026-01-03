<?php

namespace AsyncAws\CloudFormation\Enum;

final class HookStatus
{
    public const HOOK_COMPLETE_FAILED = 'HOOK_COMPLETE_FAILED';
    public const HOOK_COMPLETE_SUCCEEDED = 'HOOK_COMPLETE_SUCCEEDED';
    public const HOOK_FAILED = 'HOOK_FAILED';
    public const HOOK_IN_PROGRESS = 'HOOK_IN_PROGRESS';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HOOK_COMPLETE_FAILED => true,
            self::HOOK_COMPLETE_SUCCEEDED => true,
            self::HOOK_FAILED => true,
            self::HOOK_IN_PROGRESS => true,
        ][$value]);
    }
}
