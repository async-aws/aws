<?php

namespace AsyncAws\S3\Enum;

final class BucketNamespace
{
    public const ACCOUNT_REGIONAL = 'account-regional';
    public const GLOBAL = 'global';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ACCOUNT_REGIONAL => true,
            self::GLOBAL => true,
        ][$value]);
    }
}
