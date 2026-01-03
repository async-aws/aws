<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Optional field, defaults to DEFAULT. Specify DEFAULT for this operation to return your endpoints if any exist, or to
 * create an endpoint for you and return it if one doesn't already exist. Specify GET_ONLY to return your endpoints if
 * any exist, or an empty list if none exist.
 */
final class DescribeEndpointsMode
{
    public const DEFAULT = 'DEFAULT';
    public const GET_ONLY = 'GET_ONLY';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DEFAULT => true,
            self::GET_ONLY => true,
        ][$value]);
    }
}
