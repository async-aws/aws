<?php

namespace AsyncAws\Rekognition\Enum;

/**
 * A string value of the KnownGender info about the Celebrity.
 */
final class KnownGenderType
{
    public const FEMALE = 'Female';
    public const MALE = 'Male';

    public static function exists(string $value): bool
    {
        return isset([
            self::FEMALE => true,
            self::MALE => true,
        ][$value]);
    }
}
