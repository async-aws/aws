<?php

namespace AsyncAws\ImageBuilder\Enum;

final class Ownership
{
    public const AMAZON = 'Amazon';
    public const AWSMARKETPLACE = 'AWSMarketplace';
    public const SELF = 'Self';
    public const SHARED = 'Shared';
    public const THIRD_PARTY = 'ThirdParty';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AMAZON => true,
            self::AWSMARKETPLACE => true,
            self::SELF => true,
            self::SHARED => true,
            self::THIRD_PARTY => true,
        ][$value]);
    }
}
