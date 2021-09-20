<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The Conflict Resolution strategy to perform in the event of a conflict.
 *
 * - **OPTIMISTIC_CONCURRENCY**: Resolve conflicts by rejecting mutations when versions do not match the latest version
 *   at the server.
 * - **AUTOMERGE**: Resolve conflicts with the Automerge conflict resolution strategy.
 * - **LAMBDA**: Resolve conflicts with a Lambda function supplied in the LambdaConflictHandlerConfig.
 */
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
