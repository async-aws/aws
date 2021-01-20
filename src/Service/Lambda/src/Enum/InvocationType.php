<?php

namespace AsyncAws\Lambda\Enum;

/**
 * Choose from the following options.
 *
 * - `RequestResponse` (default) - Invoke the function synchronously. Keep the connection open until the function
 *   returns a response or times out. The API response includes the function response and additional data.
 * - `Event` - Invoke the function asynchronously. Send events that fail multiple times to the function's dead-letter
 *   queue (if it's configured). The API response only includes a status code.
 * - `DryRun` - Validate parameter values and verify that the user or role has permission to invoke the function.
 */
final class InvocationType
{
    public const DRY_RUN = 'DryRun';
    public const EVENT = 'Event';
    public const REQUEST_RESPONSE = 'RequestResponse';

    public static function exists(string $value): bool
    {
        return isset([
            self::DRY_RUN => true,
            self::EVENT => true,
            self::REQUEST_RESPONSE => true,
        ][$value]);
    }
}
