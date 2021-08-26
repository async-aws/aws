<?php

namespace AsyncAws\CloudWatch\Enum;

/**
 * The status of the returned data. `Complete` indicates that all data points in the requested time range were returned.
 * `PartialData` means that an incomplete set of data points were returned. You can use the `NextToken` value that was
 * returned and repeat your request to get more data points. `NextToken` is not returned if you are performing a math
 * expression. `InternalError` indicates that an error occurred. Retry your request using `NextToken`, if present.
 */
final class StatusCode
{
    public const COMPLETE = 'Complete';
    public const INTERNAL_ERROR = 'InternalError';
    public const PARTIAL_DATA = 'PartialData';

    public static function exists(string $value): bool
    {
        return isset([
            self::COMPLETE => true,
            self::INTERNAL_ERROR => true,
            self::PARTIAL_DATA => true,
        ][$value]);
    }
}
