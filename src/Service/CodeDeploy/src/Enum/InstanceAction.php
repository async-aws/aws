<?php

namespace AsyncAws\CodeDeploy\Enum;

final class InstanceAction
{
    public const KEEP_ALIVE = 'KEEP_ALIVE';
    public const TERMINATE = 'TERMINATE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::KEEP_ALIVE => true,
            self::TERMINATE => true,
        ][$value]);
    }
}
