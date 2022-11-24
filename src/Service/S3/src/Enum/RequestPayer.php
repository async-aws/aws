<?php

namespace AsyncAws\S3\Enum;

/**
 * Confirms that the requester knows that she or he will be charged for the list objects request. Bucket owners need not
 * specify this parameter in their requests.
 */
final class RequestPayer
{
    public const REQUESTER = 'requester';

    public static function exists(string $value): bool
    {
        return isset([
            self::REQUESTER => true,
        ][$value]);
    }
}
