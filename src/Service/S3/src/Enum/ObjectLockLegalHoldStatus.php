<?php

namespace AsyncAws\S3\Enum;

class ObjectLockLegalHoldStatus
{
    public const OFF = 'OFF';
    public const ON = 'ON';
    public const AVAILABLE_OBJECTLOCKLEGALHOLDSTATUS = [
        self::OFF => true,
        self::ON => true,
    ];
}
