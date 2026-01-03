<?php

namespace AsyncAws\DynamoDb\Enum;

final class BillingMode
{
    public const PAY_PER_REQUEST = 'PAY_PER_REQUEST';
    public const PROVISIONED = 'PROVISIONED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::PAY_PER_REQUEST => true,
            self::PROVISIONED => true,
        ][$value]);
    }
}
