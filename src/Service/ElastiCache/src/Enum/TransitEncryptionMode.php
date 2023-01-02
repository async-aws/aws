<?php

namespace AsyncAws\ElastiCache\Enum;

/**
 * A setting that allows you to migrate your clients to use in-transit encryption, with no downtime.
 */
final class TransitEncryptionMode
{
    public const PREFERRED = 'preferred';
    public const REQUIRED = 'required';

    public static function exists(string $value): bool
    {
        return isset([
            self::PREFERRED => true,
            self::REQUIRED => true,
        ][$value]);
    }
}
