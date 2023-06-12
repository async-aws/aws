<?php

namespace AsyncAws\AppSync\Enum;

final class ConflictHandlerType
{
    public const AUTOMERGE = 'AUTOMERGE';
    public const LAMBDA = 'LAMBDA';
    public const NONE = 'NONE';
    public const OPTIMISTIC_CONCURRENCY = 'OPTIMISTIC_CONCURRENCY';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTOMERGE => true,
            self::LAMBDA => true,
            self::NONE => true,
            self::OPTIMISTIC_CONCURRENCY => true,
        ][$value]);
    }
}
