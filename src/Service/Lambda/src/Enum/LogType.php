<?php

namespace AsyncAws\Lambda\Enum;

class LogType
{
    public const NONE = 'None';
    public const TAIL = 'Tail';
    public const AVAILABLE_LOGTYPE = [
        self::NONE => true,
        self::TAIL => true,
    ];
}
