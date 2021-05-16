<?php

namespace AsyncAws\Kinesis\Enum;

/**
 * The current status of the stream being described. The stream status is one of the following states:.
 *
 * - `CREATING` - The stream is being created. Kinesis Data Streams immediately returns and sets `StreamStatus` to
 *   `CREATING`.
 * - `DELETING` - The stream is being deleted. The specified stream is in the `DELETING` state until Kinesis Data
 *   Streams completes the deletion.
 * - `ACTIVE` - The stream exists and is ready for read and write operations or deletion. You should perform read and
 *   write operations only on an `ACTIVE` stream.
 * - `UPDATING` - Shards in the stream are being merged or split. Read and write operations continue to work while the
 *   stream is in the `UPDATING` state.
 */
final class StreamStatus
{
    public const ACTIVE = 'ACTIVE';
    public const CREATING = 'CREATING';
    public const DELETING = 'DELETING';
    public const UPDATING = 'UPDATING';

    public static function exists(string $value): bool
    {
        return isset([
            self::ACTIVE => true,
            self::CREATING => true,
            self::DELETING => true,
            self::UPDATING => true,
        ][$value]);
    }
}
