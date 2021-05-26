<?php

namespace AsyncAws\CloudWatchLogs\Enum;

/**
 * If the value is `LogStreamName`, the results are ordered by log stream name. If the value is `LastEventTime`, the
 * results are ordered by the event time. The default value is `LogStreamName`.
 * If you order the results by event time, you cannot specify the `logStreamNamePrefix` parameter.
 * `lastEventTimestamp` represents the time of the most recent log event in the log stream in CloudWatch Logs. This
 * number is expressed as the number of milliseconds after Jan 1, 1970 00:00:00 UTC. `lastEventTimestamp` updates on an
 * eventual consistency basis. It typically updates in less than an hour from ingestion, but in rare situations might
 * take longer.
 */
final class OrderBy
{
    public const LAST_EVENT_TIME = 'LastEventTime';
    public const LOG_STREAM_NAME = 'LogStreamName';

    public static function exists(string $value): bool
    {
        return isset([
            self::LAST_EVENT_TIME => true,
            self::LOG_STREAM_NAME => true,
        ][$value]);
    }
}
