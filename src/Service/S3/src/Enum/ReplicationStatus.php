<?php

namespace AsyncAws\S3\Enum;

class ReplicationStatus
{
    public const COMPLETE = 'COMPLETE';
    public const FAILED = 'FAILED';
    public const PENDING = 'PENDING';
    public const REPLICA = 'REPLICA';
    public const AVAILABLE_REPLICATIONSTATUS = [
        self::COMPLETE => true,
        self::FAILED => true,
        self::PENDING => true,
        self::REPLICA => true,
    ];
}
