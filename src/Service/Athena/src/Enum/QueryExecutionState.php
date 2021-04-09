<?php

namespace AsyncAws\Athena\Enum;

/**
 * The state of query execution. `QUEUED` indicates that the query has been submitted to the service, and Athena will
 * execute the query as soon as resources are available. `RUNNING` indicates that the query is in execution phase.
 * `SUCCEEDED` indicates that the query completed without errors. `FAILED` indicates that the query experienced an error
 * and did not complete processing. `CANCELLED` indicates that a user input interrupted query execution.
 *
 * > Athena automatically retries your queries in cases of certain transient errors. As a result, you may see the query
 * > state transition from `RUNNING` or `FAILED` to `QUEUED`.
 */
final class QueryExecutionState
{
    public const CANCELLED = 'CANCELLED';
    public const FAILED = 'FAILED';
    public const QUEUED = 'QUEUED';
    public const RUNNING = 'RUNNING';
    public const SUCCEEDED = 'SUCCEEDED';

    public static function exists(string $value): bool
    {
        return isset([
            self::CANCELLED => true,
            self::FAILED => true,
            self::QUEUED => true,
            self::RUNNING => true,
            self::SUCCEEDED => true,
        ][$value]);
    }
}
