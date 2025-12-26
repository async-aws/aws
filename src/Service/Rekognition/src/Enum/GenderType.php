<?php

namespace AsyncAws\Rekognition\Enum;

final class GenderType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const FEMALE = 'Female';
    public const MALE = 'Male';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FEMALE => true,
            self::MALE => true,
        ][$value]);
    }
}
