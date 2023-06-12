<?php

namespace AsyncAws\Scheduler\Enum;

final class PropagateTags
{
    public const TASK_DEFINITION = 'TASK_DEFINITION';

    public static function exists(string $value): bool
    {
        return isset([
            self::TASK_DEFINITION => true,
        ][$value]);
    }
}
