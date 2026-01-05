<?php

namespace AsyncAws\S3Vectors\Enum;

final class DistanceMetric
{
    public const COSINE = 'cosine';
    public const EUCLIDEAN = 'euclidean';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::COSINE => true,
            self::EUCLIDEAN => true,
        ][$value]);
    }
}
