<?php

namespace AsyncAws\CloudWatchLogs\Enum;

final class OrderBy
{
    public const LAST_EVENT_TIME = 'LastEventTime';
    public const LOG_STREAM_NAME = 'LogStreamName';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LAST_EVENT_TIME => true,
            self::LOG_STREAM_NAME => true,
        ][$value]);
    }
}
