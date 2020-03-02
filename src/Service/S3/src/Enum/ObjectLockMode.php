<?php

namespace AsyncAws\S3\Enum;

class ObjectLockMode
{
    public const COMPLIANCE = 'COMPLIANCE';
    public const GOVERNANCE = 'GOVERNANCE';
    public const AVAILABLE_OBJECTLOCKMODE = [
        self::COMPLIANCE => true,
        self::GOVERNANCE => true,
    ];
}
