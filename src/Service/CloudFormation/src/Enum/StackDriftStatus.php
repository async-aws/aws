<?php

namespace AsyncAws\CloudFormation\Enum;

class StackDriftStatus
{
    public const DRIFTED = 'DRIFTED';
    public const IN_SYNC = 'IN_SYNC';
    public const NOT_CHECKED = 'NOT_CHECKED';
    public const UNKNOWN = 'UNKNOWN';
    public const AVAILABLE_STACKDRIFTSTATUS = [
        self::DRIFTED => true,
        self::IN_SYNC => true,
        self::NOT_CHECKED => true,
        self::UNKNOWN => true,
    ];
}
