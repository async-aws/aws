<?php

namespace AsyncAws\S3\Enum;

final class ReplicationStatus
{
    public const COMPLETE = 'COMPLETE';
    public const COMPLETED = 'COMPLETED';
    public const FAILED = 'FAILED';
    public const PENDING = 'PENDING';
    public const REPLICA = 'REPLICA';

    public static function exists(string $value): bool
    {
        return isset([
            self::COMPLETE => true,
            self::COMPLETED => true,
            self::FAILED => true,
            self::PENDING => true,
            self::REPLICA => true,
        ][$value]);
    }
}
