<?php

namespace AsyncAws\DynamoDb\Enum;

final class VectorDistanceFunction
{
    public const COSINE = 'COSINE';
    public const DOT_PRODUCT = 'DOT_PRODUCT';
    public const EUCLIDEAN = 'EUCLIDEAN';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COSINE => true,
            self::DOT_PRODUCT => true,
            self::EUCLIDEAN => true,
        ][$value]);
    }
}
